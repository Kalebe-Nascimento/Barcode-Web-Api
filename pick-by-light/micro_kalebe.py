
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
        return data
    else:
        return None

def main():
    LedPins = [31, 33, 35, 37]
    
    GPIO.setwarnings(False)

    GPIO.setmode(GPIO.BOARD)
    GPIO.setup(LedPins, GPIO.OUT)
    GPIO.output(LedPins, GPIO.LOW)
    
    def update_led(upc):
        product_data = UPC_lookup(api_key, upc)
        if product_data:
            barcode = product_data.get('barcode')
            if barcode:
                first_digit = int(barcode[0])
                led_index = first_digit % len(LedPins)
                GPIO.output(LedPins[led_index], GPIO.HIGH)
                time.sleep(6)
                GPIO.output(LedPins[led_index], GPIO.LOW)
            else:
                GPIO.output(LedPins, GPIO.LOW)

    try:
        while True:
            upc_code = input('Code -> ')
            update_led(upc_code)
            time.sleep(5)
    except KeyboardInterrupt:
        pass
    finally:
        GPIO.cleanup()

if __name__ == '__main__':
    main()
