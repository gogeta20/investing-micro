from app.infrastructure.ml.intent_classifier import IntentClassifierService

def test_classifier_predict_saludo():
    classifier = IntentClassifierService()
    intent, confidence = classifier.predict("hola")

    assert intent == "saludo"
    assert confidence >= 0.6  # margen de seguridad

def test_classifier_predict_invalido():
    classifier = IntentClassifierService()
    intent, confidence = classifier.predict("zzzzzzzzz")

    assert confidence < 0.7
