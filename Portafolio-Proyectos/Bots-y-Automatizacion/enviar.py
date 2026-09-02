import requests

def enviar_mensaje(info):
    # --- 1. TUS CREDENCIALES ---
    # Reemplaza esto con el token larguísimo que generaste
    TOKEN = "EAAbiqvdu6agBSUv7JHRpY4GxoBUpVash4TBXnbdk6LkivszOva3R7jaHiPVROoQ2yUcTbTf1ofkId4G8yYTcZBqWDC1vKf0nsiZAdptAGGk0L01bYWTXZC7DZBYuAVAZBkbrX3KxqvZCCbfZBnlPMDPE3QJNAZBAcy0ZAotFRgUn5hoBWnYMUKy9DuMf0erW79umQKL8WkGwwiTCmCByH1CvvtMoyhxVSLC3W9Sd081xs0M9GDsu0tLrZCk2k9cqadRMODbqyEQkIkDh3cM3OCryC7"

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
            "body": info
        }
    }

    # --- 3. ENVIAR EL MENSAJE ---
    print("Enviando mensaje a Meta...")
    respuesta = requests.post(url, headers=headers, json=data)

    # --- 4. VER EL RESULTADO ---
    print("Código de estado:", respuesta.status_code)
    print("Respuesta del servidor:", respuesta.json())