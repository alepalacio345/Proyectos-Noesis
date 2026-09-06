import requests
from detenv import load_dotenv

#llamamos a la funcion con las llaves
load_dotenv()

def respuesta_bot(info, numero_destino, tipo="texto"):
    
    
    TOKEN = os.getenv("TOKEN")
    PHONE_NUMBER_ID = os.getenv("PHONE_NUMBER_ID")

    # --- 2. CONFIGURACIÓN DE LA PETICIÓN ---
    # Esta es la dirección oficial a la que le tocamos la puerta a Meta
    url = f"https://graph.facebook.com/v20.0/{PHONE_NUMBER_ID}/messages"
    headers = {
        "Authorization": f"Bearer {TOKEN}",
        "Content-Type": "application/json"
    }

    # Si pedimos enviar botones, armamos el JSON interactivo (Máximo 3 botones permitidos por Meta)
    if tipo == "botones":
        data = {
            "messaging_product": "whatsapp",
            "to": numero_destino,
            "type": "interactive",
            "interactive": {
                "type": "button",
                "body": {
                    "text": info
                },
                "action": {
                    "buttons": [
                        {
                            "type": "reply",
                            "reply": {
                                "id": "pago", # Este es el texto oculto que lee tu bot
                                "title": "💳 Pago" # Esto es lo que ve el usuario en pantalla
                            }
                        },
                        {
                            "type": "reply",
                            "reply": {
                                "id": "proyectos",
                                "title": "🚀 Proyectos"
                            }
                        },
                        {
                            "type": "reply",
                            "reply": {
                                "id": "productos",
                                "title": "🛒 Productos"
                            }
                        }
                    ]
                }
            }
        }
    else:
        # Si no, enviamos un mensaje de texto normal
        data = {
            "messaging_product": "whatsapp",
            "to": numero_destino,
            "type": "text",
            "text": {
                "body": info
            }
        }

    print(f"Enviando respuesta a {numero_destino}...")
    respuesta = requests.post(url, headers=headers, json=data)
    print("Código de estado:", respuesta.status_code)