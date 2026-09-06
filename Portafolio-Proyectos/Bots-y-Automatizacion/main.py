from fastapi import FastAPI, Request
from fastapi.responses import PlainTextResponse
from respuesta import respuesta_bot
import time
from detenv import load_dotenv

load_dotenv()
TOKEN_SECRETO = os.getenv("TOKEN_SECRETO")
app = FastAPI()

sesiones_usuarios = {}
TIEMPO_DE_SESION = 120

@app.get("/")
def verificar(request: Request):
    hub_mode = request.query_params.get("hub.mode")
    hub_challenge = request.query_params.get("hub.challenge")
    hub_verify_token = request.query_params.get("hub.verify_token")

    if hub_mode == "subscribe" and hub_verify_token == TOKEN_SECRETO:
        print("¡ÉXITO! Token validado correctamente.")
        return PlainTextResponse(content=hub_challenge)
    
    return PlainTextResponse(content="Token invalido", status_code=403)

@app.post("/")
async def recibir_mensaje(request: Request):
    diccionario_general = await request.json()

    try:
        # Atrapamos el bloque del mensaje para extraer datos sin que quede tan largo
        mensaje_data = diccionario_general['entry'][0]['changes'][0]["value"]["messages"][0]
        id_usuario = mensaje_data["from"]
        tiempo_actual = time.time() 

        if id_usuario not in sesiones_usuarios:
            sesiones_usuarios[id_usuario] = 0

        tiempo_inactivo = tiempo_actual - sesiones_usuarios[id_usuario]

        if tiempo_inactivo > TIEMPO_DE_SESION:
            sesiones_usuarios[id_usuario] = tiempo_actual
            
            bienvenida = "¡Hola! 👋 Soy *Gustavo*, tu asistente virtual.\n\nEs un gran placer saludarte. ¿En qué te puedo ayudar el día de hoy? 👇"
            # Le pasamos el parámetro tipo="botones" para que mande el menú interactivo
            respuesta_bot(bienvenida, id_usuario, tipo="botones")
            
        else:    
            # Validación inteligente: detecta si escribieron a mano o tocaron un botón
            if "text" in mensaje_data:
                texto_mensaje = mensaje_data["text"]["body"].lower()
            
            elif "interactive" in mensaje_data:
                texto_mensaje = mensaje_data["interactive"]["button_reply"]["id"].lower()
            
            elif ("audio" in mensaje_data) or ("image" in mensaje_data):
                texto_mensaje = "multimedia"
        
            else:
                texto_mensaje = "" # Por si mandan una video, stiker, o algo raro

            match texto_mensaje:
                case "pago":
                    msj_pago = (
                        "¡Claro que sí! 💳 Aquí tienes los datos para realizar tu transferencia de forma segura:\n\n"
                        "👤 *Titular:* Saúl Alejandro Lara Palacio\n"
                        "🪪 *C.I:* 31.456.395\n"
                        "🏦 *Banco:* Banco de Venezuela (0102)\n"
                        "📱 *Teléfono:* 0412-7426581\n\n"
                        "Por favor, envíame la captura del comprobante por aquí mismo cuando estés listo. ✅"
                    )
                    respuesta_bot(msj_pago, id_usuario)

                case "proyectos":
                    msj_proyectos = (
                        "¡Genial! 🚀 Actualmente mi creador está trabajando en estas iniciativas increíbles:\n\n"
                        "⛴️ *Ferrys:* Sistema de gestión y logística.\n"
                        "📻 *Radio:* Plataforma moderna de transmisión.\n"
                        "👾 *Pokémon:* Desarrollo estructural de El Gran Torneo en lenguaje C.\n\n"
                        "¿Deseas saber más detalles sobre alguno?"
                    )
                    respuesta_bot(msj_proyectos, id_usuario)

                case "productos":
                    msj_productos = (
                        "¡Excelente elección! 🛒 Aquí tienes nuestra lista de inventario disponible hoy:\n\n"
                        "🌽 Harina Pan\n"
                        "🍚 Arroz Entero\n"
                        "🍝 Pasta Larga\n"
                        "🍬 Azúcar Refinada\n\n"
                        "Indícame cuáles deseas encargar y te tomaré el pedido de inmediato."
                    )
                    respuesta_bot(msj_productos, id_usuario)

                case "quien eres":
                    msj_quien_soy = (
                        "¡Hola! 🤖 Soy *Gustavo*, tu asistente virtual de confianza.\n\n"
                        "Fui programado con mucho cuidado por mi creador, *Saúl Alejandro Lara Palacio*, "
                        "para automatizar tareas y brindar respuestas rápidas. ¡Estoy aquí para hacerte la vida más fácil! ✨"
                    )
                    respuesta_bot(msj_quien_soy, id_usuario)

                case "multimedia":
                    msj_multimedia = (
                        "Ups... 😅 Me encantaría ver o escuchar eso, pero por ahora mis sistemas solo logran "
                        "entender mensajes de texto. 🤖💬\n\n"
                        "Por favor, escríbeme tu consulta o selecciona una de las opciones del menú para poder ayudarte."
                    )
                    respuesta_bot(msj_multimedia, id_usuario)

                case _:
                    print("Opción inválida recibida")
                    msj_error = (
                        "Ups... 😅 No logré entender ese comando.\n\n"
                        "Por favor, elige una de las opciones del menú principal tocando los botones, "
                        "o escribe *pago*, *proyectos* o *productos*."
                    )
                    respuesta_bot(msj_error, id_usuario)

    except KeyError:
        pass # Ignoramos errores de estado silenciosamente (como notificaciones de mensaje leído)

    return {"status": "ok"}