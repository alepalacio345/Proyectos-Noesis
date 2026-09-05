import requests

def respuesta_bot(info, numero_destino, tipo="texto"):
    # --- 1. TUS CREDENCIALES ---
    TOKEN = "EAAbiqvdu6agBSdDWojZApZBC0avwMBBihhthIrs2DRCyY9Ux5P5fw41DNhRZBk8ZBvFgxq9baSG9jSEKEdge9CS5pMHSKwNKSGLOjnDTBZCt6fZClqpXyLx4u066dPHOjlYbAn13PJNNPMqVynhxiRWnLRSbrlFqMrpauom1ZAK0AujnyM0FL4hawx7iCZCjIgZDZD"
    PHONE_NUMBER_ID = "1345165362005492"

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