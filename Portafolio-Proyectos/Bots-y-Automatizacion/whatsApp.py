from fastapi import FastAPI, Request
from fastapi.responses import PlainTextResponse

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
    datos = await request.json()
    print("Mensaje recibido:", datos)
    return {"status": "ok"}