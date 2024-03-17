import requests

url = 'https://evertonfranca.com.br/smtyts/receber_dados.php'

data = {
    'barcode': '1234567830113',
    'added_time': '2023-03-15 08:00:00',
    'modified_time': '2023-03-15 08:30:00',
    'title': 'Produto 1',
    'description': 'Descrição do Produto 1',
    'brand': 'Marca A',
    'manufacturing': 'Fabricante X'
}

response = requests.post(url, data=data)

print(response.text)