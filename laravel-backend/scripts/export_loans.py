import pandas as pd
import requests

# URL de l'API Laravel
API_URL = 'http://localhost:8000/api/loans'

# Remplacez par votre token d'accès
ACCESS_TOKEN = 'Bearer 6|2lkSHXSbEATuukRhOxgO5zOm8mngqCVblO0riAah63743c0a'

# Effectuer une requête GET pour récupérer les emprunts
response = requests.get(API_URL, headers={'Authorization': f'Bearer {ACCESS_TOKEN}'})

# Vérifier si la requête a réussi
if response.status_code == 200:
    loans = response.json()
    
    # Convertir les données en DataFrame pandas
    df = pd.DataFrame(loans)
    
    # Exporter les données en fichier CSV
    df.to_csv('loans_report.csv', index=False)
    print("Rapport des emprunts exporté avec succès en loans_report.csv")
else:
    print(f"Erreur lors de la récupération des emprunts: {response.status_code}")