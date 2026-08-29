import requests

# --- 1. TUS CREDENCIALES ---
# Reemplaza esto con el token larguísimo que generaste
TOKEN = "EAAbiqvdu6agBSbHIAVayy1N2gl0gsZA6o2gTzbuUrqARjAgs1LDi3aPWuprcQZCjj2MHUfnybwZAZBw2l0ln6sWMSOvyB12CX14nXq7YjD56kEI7PerDrNvyrZAXgsZA3IiIC19KPZCP06sxXto3o2HZCL8qiaBQRrckoAbFA9ocd52Pbe02m0foPuVN8KdzHnMSs98yiPOQCPm0CC7po4EQlZBPamKT6j0QbPQWE9Jpd8HAiCjMCJW6LDXzfXZAEsSpCOZB9WAGcZBnawvayTJkh8gaGR00"

# Reemplaza esto con el Identificador de número de teléfono (solo números)
PHONE_NUMBER_ID = "1092485180613674"

# Tu número de celular verificado. 
# MUY IMPORTANTE: Debe ir con el código de país (58), pero SIN el símbolo '+', SIN espacios y SIN guiones.
MI_NUMERO = "584127426581" 

# --- 2. CONFIGURACIÓN DE LA PETICIÓN ---
# Esta es la dirección oficial a la que le tocamos la puerta a Meta
url = f"https://graph.facebook.com/v20.0/{PHONE_NUMBER_ID}/messages"

# Los encabezados son como nuestra identificación en la puerta
headers = {
    "Authorization": f"Bearer {TOKEN}",
    "Content-Type": "application/json"
}

# El cuerpo del mensaje (lo que queremos enviar)
data = {
    "messaging_product": "whatsapp",
    "to": MI_NUMERO,
    "type": "text",
    "text": {
        "body": "¡Hola, Saúl! Este es tu primer mensaje enviado directamente desde Python 🚀"
    }
}

# --- 3. ENVIAR EL MENSAJE ---
print("Enviando mensaje a Meta...")
respuesta = requests.post(url, headers=headers, json=data)

# --- 4. VER EL RESULTADO ---
print("Código de estado:", respuesta.status_code)
print("Respuesta del servidor:", respuesta.json())