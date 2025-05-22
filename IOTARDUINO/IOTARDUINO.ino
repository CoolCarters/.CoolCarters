#include <SPI.h>
#include <MFRC522.h>

#define SS_PIN 10
#define RST_PIN 9 

MFRC522 rfid(SS_PIN, RST_PIN);

void setup() {
  Serial.begin(9600);
  SPI.begin();
  rfid.PCD_Init();
  Serial.println("RFID reader initialized.");
}

void loop() {
  if (!rfid.PICC_IsNewCardPresent())
    return;

  if (!rfid.PICC_ReadCardSerial())
    return;

  Serial.print("RFID Tag UID: ");
  printHex(rfid.uid.uidByte, rfid.uid.size);
  delay(1000);  // Avoid multiple reads

  rfid.PICC_HaltA();
}

void printHex(byte *buffer, byte bufferSize) {
  for (byte i = 0; i < bufferSize; i++) {
    if (buffer[i] < 0x10)
      Serial.print("0");
    Serial.print(buffer[i], HEX);
  }
  Serial.println();
}
