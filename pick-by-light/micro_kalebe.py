import sys
import requests
import json
import RPi.GPIO as GPIO
import time

api_key = "a94b49b6992dc798428669d3dd99914ce60aca82"

def UPC_lookup(api_key, upc):
    url = f"https://api.upcdatabase.org/product/{upc}/{api_key}"
    headers = {'Authorization': 'Bearer THISISALIVEDEMOAPIKEY19651D54X47'}
    response = requests.get(url, headers=headers)
    if response.status_code == 200:
        data = response.json()
        print(data)
        produto = {
            "barcode": data["barcode"],
            "added_time": data["added_time"],
            "modified_time": data["modified_time"],
            "title": data["title"],
            "description": data["description"],
            "brand": data["brand"],
            "manufacturing": data["manufacturer"],
        }
        apiResponse = requests.post("https://evertonfranca.com.br/smtyts/receber_dados.php", data=produto)
        print(apiResponse.text)
        return data
    else:
        return None

def main():
    LedPins = {31: ["802752002291", "0802752002291"], 
               33: ["070870006716", "0070870006716"], 
               35: ["021000605996", "0021000605996"], 
               37: ["026676699331", "0026676699331"]}
    
    BarcodeToPin = {code: pin for pin, codes in LedPins.items() for code in codes}

    GPIO.setwarnings(False)
    GPIO.setmode(GPIO.BOARD)
    GPIO.setup(list(LedPins.keys()), GPIO.OUT)
    GPIO.output(list(LedPins.keys()), GPIO.LOW)

    def update_led(upc):
        product_data = UPC_lookup(api_key, upc)
        if product_data:
            barcode = product_data.get('barcode')
            led_pin = BarcodeToPin.get(barcode)
            if led_pin is not None:
                GPIO.output(list(LedPins.keys()), GPIO.LOW)
                GPIO.output(led_pin, GPIO.HIGH) 
                time.sleep(6)
                GPIO.output(led_pin, GPIO.LOW)
            else:
                GPIO.output(list(LedPins.keys()), GPIO.LOW)
    try:
        while True:
            upc_code = input('Code: ')
            update_led(upc_code)
            time.sleep(5)
    except KeyboardInterrupt:
        pass
    finally:
        GPIO.cleanup()

if __name__ == '__main__':
    main()
