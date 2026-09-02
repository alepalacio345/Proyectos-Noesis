import requests
import os

# Apuntamos a la puerta de tu servidor local
url = "http://127.0.0.1:8000/"

bandera = True

while bandera:

    mensaje = str(input("Dime el mensaje: "))

    # Recreamos el paquete exacto que FastAPI espera recibir
    paquete_simulado = {
    "object": "whatsapp_business_account",
    "entry": [
        {
            "id": "1234567890", # ID genérico de la cuenta
            "changes": [
                {
                    "field": "messages",
                    "value": {
                        "messaging_product": "whatsapp",
                        "metadata": {
                            "display_phone_number": "16505551111",
                            "phone_number_id": "123456123"
                        },
                        "contacts": [
                            {
                                "profile": {
                                    "name": "test user name"
                                },
                                "wa_id": "16315551181",
                                "user_id": "US.13491208655302741918"
                            }
                        ],
                        "messages": [
                            {
                                "id": "ABGGFlA5Fpa",
                                "timestamp": "1504902988",
                                "from": "16315551181",
                                "from_user_id": "US.13491208655302741918",
                                "type": "text",
                                "text": {
                                    # La variable mensaje que vayas a inyectar en tu prueba
                                    "body": mensaje
                                }
                            }
                        ]
                    }
                }
            ]
        }
    ]
}

    print("Disparando mensaje simulado a FastAPI...")
    # Hacemos un POST directo a tu computadora, sin pasar por internet ni Pinggy
    respuesta = requests.post(url, json=paquete_simulado)

    continuar = input("¿Quieres enviar otro mensaje? (y/n)\n")

    if continuar == "n":
        bandera = False

    os.system("cls")
    
