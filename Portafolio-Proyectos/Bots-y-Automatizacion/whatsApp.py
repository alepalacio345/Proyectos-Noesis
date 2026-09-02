from fastapi import FastAPI, Request
from fastapi.responses import PlainTextResponse
from respuesta import respuesta_bot
import time

app = FastAPI()

TOKEN_SECRETO = "noesis_secreto_123"

sesiones_usuarios = {}
TIEMPO_DE_SESION = 120

@app.get("/")
def verificar(request: Request):
    hub_mode = request.query_params.get("hub.mode")
    hub_challenge = request.query_params.get("hub.challenge")
    hub_verify_token = request.query_params.get("hub.verify_token")

    print(f"--- META ESTÁ TOCANDO LA PUERTA ---")
    print(f"Token recibido: {hub_verify_token}")
    print(f"Challenge recibido: {hub_challenge}")

    if hub_mode == "subscribe" and hub_verify_token == TOKEN_SECRETO:
        print("¡ÉXITO! Token validado correctamente.")
        # Devolvemos el challenge en texto puro, sin formato JSON
        return PlainTextResponse(content=hub_challenge)
    
    print("Fallo: El token no coincide.")
    return PlainTextResponse(content="Token invalido", status_code=403)

@app.post("/")
async def recibir_mensaje(request: Request):
    diccionario_general = await request.json()
    # await le dice espera que se descargue todo y request.json() convierte el json en diccionario

    try:
        #id del usuario
        id_usuario = diccionario_general['entry'][0]['changes'][0]["value"]["messages"][0]["from"]
        tiempo_actual = time.time() #pedimos el tiempo actual

        if id_usuario not in sesiones_usuarios:
            sesiones_usuarios[id_usuario] = 0

        tiempo_inactivo = tiempo_actual - sesiones_usuarios[id_usuario]

        #Si pasaron más de 30 segundos (o si era nuevo y tenía tiempo 0)
        if tiempo_inactivo > TIEMPO_DE_SESION:

            sesiones_usuarios[id_usuario] = tiempo_actual
            respuesta_bot("Hola soy Gustabo es un placer\n¿En que puedo ayudarte?\n\nHacer pago de producto escribe la palabra pago\ninfo de proyectos Escribe proyectos\nInfo de creacion Escribe quien eres")
        else:    
            #accedemos al texto
            texto_mensaje = diccionario_general['entry'][0]['changes'][0]["value"]["messages"][0]["text"]["body"]
            #convierto todo en minusculas
            texto_mensaje = texto_mensaje.lower()

            if texto_mensaje == "pago":
                respuesta_bot("Estos son los datos\nCedula: 31456395\nBanco: 0102\ntelefono: 04127426581")
                    
            elif texto_mensaje == "proyectos":
                respuesta_bot("ferrys, radio, pokemon")
                    
            elif texto_mensaje == "quien eres":
                respuesta_bot("soy un asistente virtual creado para ayudarte en lo que necesites\nmi creador es Saul Alejandro Lara Palacio")
                    
            else: 
                print("opcion invalida")
                respuesta_bot("opcion invalida")

    except KeyError:
        print("Error al cargar json")

    return {"status": "ok"}