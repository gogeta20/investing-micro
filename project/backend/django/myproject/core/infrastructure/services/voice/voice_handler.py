import speech_recognition as sr

class VoiceHandler:
    def __init__(self):
        self.recognizer = sr.Recognizer()

    def capture_voice(self):
        with sr.Microphone() as source:
            print("Escuchando...")
            audio = self.recognizer.listen(source)
            try:
                text = self.recognizer.recognize_google(audio, language="es-ES")
                return text
            except sr.UnknownValueError:
                return "No pude entender el audio"
            except sr.RequestError:
                return "Error en el servicio de reconocimiento"
