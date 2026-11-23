<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Models\BusinessPaymentAccount;

class PaymentAccountManager extends Component
{
    public $accounts = [];
    public $showModal = false;
    public $editId = null;

    // Form fields
    public $method = 'pago_movil';
    public $label = '';
    public $bank = '';
    public $phone = '';
    public $id_number = '';
    public $account_number = '';
    public $account_type = 'corriente';
    public $holder_name = '';

    protected function rules()
    {
        return [
            'method' => 'required|in:pago_movil,transfer,pos_terminal',
            'label' => 'required|string|max:255',
            'bank' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'id_number' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'account_type' => 'nullable|in:corriente,ahorro',
            'holder_name' => 'nullable|string|max:255',
        ];
    }

    public function mount()
    {
        $this->loadAccounts();
    }

    public function loadAccounts()
    {
        $this->accounts = BusinessPaymentAccount::orderBy('method')->orderBy('sort_order')->orderBy('id')->get();
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $this->resetForm();
        $this->editId = $id;
        $account = BusinessPaymentAccount::findOrFail($id);
        
        $this->method = $account->method;
        $this->label = $account->label;
        $this->bank = $account->bank;
        $this->phone = $account->phone;
        $this->id_number = $account->id_number;
        $this->account_number = $account->account_number;
        $this->account_type = $account->account_type;
        $this->holder_name = $account->holder_name;
        
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'method' => $this->method,
            'label' => $this->label,
            'bank' => $this->bank,
            'phone' => $this->phone,
            'id_number' => $this->id_number,
            'account_number' => $this->account_number,
            'account_type' => $this->account_type,
            'holder_name' => $this->holder_name,
        ];

        if ($this->editId) {
            BusinessPaymentAccount::findOrFail($this->editId)->update($data);
            session()->flash('success', 'Cuenta actualizada correctamente.');
        } else {
            $data['is_active'] = true;
            BusinessPaymentAccount::create($data);
            session()->flash('success', 'Cuenta creada correctamente.');
        }

        $this->showModal = false;
        $this->loadAccounts();
    }

    public function toggleActive($id)
    {
        $account = BusinessPaymentAccount::findOrFail($id);
        $account->update(['is_active' => !$account->is_active]);
        $this->loadAccounts();
    }

    public function delete($id)
    {
        BusinessPaymentAccount::findOrFail($id)->delete();
        session()->flash('success', 'Cuenta eliminada.');
        $this->loadAccounts();
    }

    public function resetForm()
    {
        $this->resetValidation();
        $this->editId = null;
        $this->method = 'pago_movil';
        $this->label = '';
        $this->bank = '';
        $this->phone = '';
        $this->id_number = '';
        $this->account_number = '';
        $this->account_type = 'corriente';
        $this->holder_name = '';
    }

    public function render()
    {
        return view('livewire.settings.payment-account-manager');
    }
}