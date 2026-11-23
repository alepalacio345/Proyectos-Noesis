<?php

namespace App\Livewire\Pos;

use Livewire\Component;
use App\Models\Product;
use App\Models\ProductMovement;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Payment;
use App\Models\ExchangeRate;
use App\Models\Customer;
use App\Models\BusinessPaymentAccount;
use Illuminate\Support\Facades\DB;

class CashRegister extends Component
{
    // ── Search & Cart ──────────────────────────────────────────────────────────
    public string $search = '';
    public array  $cart   = [];

    // ── Payment Panel ─────────────────────────────────────────────────────────
    public bool   $showPayment  = false;
    public string $payMethod    = 'cash_usd';
    public string $payAmount    = '';
    public string $payReference = '';
    public array  $payments     = [];

    // ── Extras ────────────────────────────────────────────────────────────────
    public ?int   $customerId = null;
    public string $note       = '';
    public float  $bcvRate    = 0;

    // ── Messages ──────────────────────────────────────────────────────────────
    public ?string $successMessage = null;
    public ?string $errorMessage   = null;

    public function mount(): void
    {
        $latest        = ExchangeRate::orderBy('date', 'desc')->first();
        $this->bcvRate = $latest ? (float) $latest->rate : 0;
    }

    // ── Helpers (private, used inside render) ─────────────────────────────────

    private function calcTotalUsd(): float
    {
        return round(array_sum(array_column($this->cart, 'subtotal')), 2);
    }

    private function calcPaidUsd(): float
    {
        $total = 0;
        foreach ($this->payments as $p) {
            $amount = (float) $p['amount'];
            if ($p['currency'] === 'USD') {
                $total += $amount;
            } else {
                $total += $this->bcvRate > 0 ? round($amount / $this->bcvRate, 4) : 0;
            }
        }
        return round($total, 2);
    }

    
    // ── Barcode Scanner ───────────────────────────────────────────────────────

    public function scanProduct(): void
    {
        $barcode = trim($this->search);
        if (empty($barcode)) return;

        // Try exact match by barcode first
        $product = Product::where('barcode', $barcode)->first();

        if ($product) {
            $this->addToCart($product->id);
            $this->search = ''; // Clear input for the next scan
            return;
        }

        // Optional fallback: If they typed an exact name or ID by mistake
        $productFallback = Product::where('name', $barcode)->first();
        if ($productFallback) {
            $this->addToCart($productFallback->id);
            $this->search = '';
            return;
        }

        $this->errorMessage = "Producto no encontrado con el código: " . $barcode;
    }

    // ── Cart ──────────────────────────────────────────────────────────────────

    public function addToCart(int $productId): void
    {
        $product = Product::find($productId);
        if (! $product) return;

        foreach ($this->cart as $i => $item) {
            if ($item['product_id'] === $productId) {
                if ($this->cart[$i]['qty'] < $product->stock) {
                    $this->cart[$i]['qty']++;
                    $this->cart[$i]['subtotal'] = round($this->cart[$i]['qty'] * $this->cart[$i]['price'], 2);
                }
                return;
            }
        }

        if ($product->stock <= 0) {
            $this->errorMessage = "Sin stock: {$product->name}";
            return;
        }

        $this->cart[] = [
            'product_id' => $product->id,
            'name'       => $product->name,
            'price'      => (float) $product->sale_price,
            'qty'        => 1,
            'subtotal'   => (float) $product->sale_price,
            'stock'      => $product->stock,
        ];
        $this->errorMessage   = null;
        $this->successMessage = null;
    }

    public function removeFromCart(int $index): void
    {
        array_splice($this->cart, $index, 1);
        $this->cart = array_values($this->cart);
    }

    public function updateQty(int $index, int $qty): void
    {
        if ($qty < 1) {
            $this->removeFromCart($index);
            return;
        }
        $maxStock = $this->cart[$index]['stock'] ?? 999;
        $this->cart[$index]['qty']      = min($qty, $maxStock);
        $this->cart[$index]['subtotal'] = round($this->cart[$index]['qty'] * $this->cart[$index]['price'], 2);
    }

    public function clearCart(): void
    {
        $this->cart           = [];
        $this->payments       = [];
        $this->showPayment    = false;
        $this->errorMessage   = null;
        $this->successMessage = null;
    }

    // ── Payment ───────────────────────────────────────────────────────────────

    public function openPayment(): void
    {
        if (empty($this->cart)) {
            $this->errorMessage = 'El carrito está vacío.';
            return;
        }
        $this->payments     = [];
        $this->payAmount    = '';
        $this->payReference = '';
        $this->payMethod    = 'cash_usd';
        $this->showPayment  = true;
        $this->errorMessage = null;
    }

    public function closePayment(): void
    {
        $this->showPayment  = false;
        $this->errorMessage = null;
    }

    public function addPayment(): void
    {
        $amount = (float) $this->payAmount;
        if ($amount <= 0) {
            $this->errorMessage = 'Ingresa un monto válido.';
            return;
        }

        $currency = in_array($this->payMethod, ['cash_usd', 'zelle']) ? 'USD' : 'VES';

        $this->payments[] = [
            'method'    => $this->payMethod,
            'amount'    => $amount,
            'currency'  => $currency,
            'reference' => trim($this->payReference),
        ];

        $this->payAmount    = '';
        $this->payReference = '';
        $this->errorMessage = null;
    }

    public function removePayment(int $index): void
    {
        array_splice($this->payments, $index, 1);
        $this->payments = array_values($this->payments);
    }

    // ── Process Sale ──────────────────────────────────────────────────────────

    public function processSale(): void
    {
        $totalUsd = $this->calcTotalUsd();
        $paidUsd  = $this->calcPaidUsd();
        $change   = round($paidUsd - $totalUsd, 2);

        if (empty($this->cart)) {
            $this->errorMessage = 'El carrito está vacío.';
            return;
        }
        if (empty($this->payments)) {
            $this->errorMessage = 'Agrega al menos un pago.';
            return;
        }
        if ($change < -0.01) {
            $this->errorMessage = 'Monto insuficiente. Falta: $' . number_format(abs($change), 2);
            return;
        }

        DB::transaction(function () use ($totalUsd) {
            $sale = Sale::create([
                'customer_id'   => $this->customerId,
                'total'         => $totalUsd,
                'exchange_rate' => $this->bcvRate,
                'status'        => 'completed',
                'notes'         => $this->note ?: null,
            ]);

            foreach ($this->cart as $item) {
                SaleDetail::create([
                    'sale_id'    => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['qty'],
                    'price'      => $item['price'],
                    'subtotal'   => $item['subtotal'],
                ]);

                $product = Product::find($item['product_id']);
                if ($product) {
                    $previousStock = $product->stock;
                    $product->decrement('stock', $item['qty']);

                    if ((bool) $product->is_perishable) {
                        $remainingToReduce = $item['qty'];
                        foreach($product->activeBatches as $batch) {
                            if ($remainingToReduce <= 0) break;
                            if ($batch->current_stock >= $remainingToReduce) {
                                $batch->decrement('current_stock', $remainingToReduce);
                                $remainingToReduce = 0;
                            } else {
                                $remainingToReduce -= $batch->current_stock;
                                $batch->update(['current_stock' => 0]);
                            }
                        }
                    }
                    
                    ProductMovement::create([
                        'product_id'     => $product->id,
                        'type'           => 'out',
                        'quantity'       => $item['qty'],
                        'previous_stock' => $previousStock,
                        'new_stock'      => $previousStock - $item['qty'],
                        'reason'         => "Venta POS #" . $sale->id
                    ]);
                }
            }

            foreach ($this->payments as $p) {
                Payment::create([
                    'sale_id'          => $sale->id,
                    'payment_method'   => $p['method'],
                    'amount'           => $p['amount'],
                    'currency'         => $p['currency'],
                    'exchange_rate'    => $this->bcvRate,
                    'reference_number' => $p['reference'] ?: null,
                ]);
            }
        });

        $this->cart           = [];
        $this->payments       = [];
        $this->showPayment    = false;
        $this->customerId     = null;
        $this->note           = '';
        $this->successMessage = '¡Venta completada exitosamente!';
        $this->errorMessage   = null;
    }

    // ── Render ────────────────────────────────────────────────────────────────

    public function render()
    {
        // Compute totals here and pass to the view explicitly
        $totalUsd = $this->calcTotalUsd();
        $totalBs  = round($totalUsd * $this->bcvRate, 2);
        $paidUsd  = $this->calcPaidUsd();
        $changeUsd = round($paidUsd - $totalUsd, 2);

        $products = Product::with('category')
            ->where('stock', '>', 0)
            ->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('barcode', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name')
            ->limit(40)
            ->get();

        $customers = Customer::orderBy('name')->get();

                $businessAccounts = BusinessPaymentAccount::where('is_active', true)->orderBy('sort_order')->get();

        return view('livewire.pos.cash-register', compact(
            'products', 'customers',
            'totalUsd', 'totalBs', 'paidUsd', 'changeUsd',
            'businessAccounts'
        ));
    }
}