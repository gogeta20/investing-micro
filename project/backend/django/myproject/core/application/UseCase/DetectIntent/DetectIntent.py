class DetectIntent:
    def __init__(self,intent_classifier_service):
        self.classifier = intent_classifier_service

    def execute(self, text):
        ml_result = self.process_text(text)

        return {
            "intent": ml_result["intent"],
            "confidence": ml_result["confidence"],
        }

    def process_text(self, text):
        text = text.lower()
        intent, confidence = self.classifier.predict(text)

        return {
            "intent": intent,
            "confidence": confidence,
        }
