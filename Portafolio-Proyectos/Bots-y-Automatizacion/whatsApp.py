from fastapi import FastAPI, Request
from fastapi.responses import PlainTextResponse
from enviar import enviar_mensaje

app = FastAPI()

TOKEN_SECRETO = "noesis_secreto_123"

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
        #meta datos o datos de cabezera
        sub_diccionario = diccionario_general['entry'][0]['changes'][0]

        #accedemos al texto
        texto_mensaje = sub_diccionario["value"]["messages"][0]["text"]["body"]

        #convierto todo en minusculas
        texto_mensaje = texto_mensaje.lower()

        if texto_mensaje == "this is a text message":

            enviar_mensaje("Estos son los datos\nCedula: 31456395\nBanco: 0102\ntelefono: 04127426581")

        elif texto_mensaje == "proyectos":

            enviar_mensaje("ferrys, radio, pokemon")

        elif texto_mensaje == "quien eres":

            enviar_mensaje("soy un asistente virtual creado para ayudarte en lo que necesites\n mi creador es Saul lara")

        else: 
            print("opcion invalida")
            enviar_mensaje("opcion invalida")

    except KeyError:
        print("Error al cargar json")

    return {"status": "ok"}