from flask import Flask, request, jsonify
from flask_cors import CORS  # Importar CORS
import numpy as np
import pandas as pd
from sklearn.model_selection import train_test_split
from sklearn.preprocessing import StandardScaler
from sklearn.ensemble import RandomForestClassifier
from sklearn.metrics import accuracy_score, confusion_matrix, classification_report

# Inicializar Flask
app = Flask(__name__)
CORS(app, resources={r"/*": {"origins": "*"}})

# Cargar y preparar los datos
df = pd.read_csv('ia/datos_csv.csv', sep=';')
columnas_deseadas = ['RUC_EMPLEADOR', 'TIPO_EMPRESA', 'MTO_TOTAL_DEMANDA', 'DEPARTAMENTO', 'ESTADO']
df_filtrado = df[columnas_deseadas]
df_filtrado['ESTADO'] = df_filtrado['ESTADO'].replace({'Retrasado': 1, 'Finalizado': 0})
df_filtrado['TIPO_EMPRESA'] = df_filtrado['TIPO_EMPRESA'].replace({'PRIVADA': 1, 'PUBLICAS': 0})
df_filtrado['MTO_TOTAL_DEMANDA'] = df_filtrado['MTO_TOTAL_DEMANDA'].str.replace('.', '').str.replace(',', '.').astype(float)

departamentos_numeros = {
    'AMAZONAS': 1, 'ANCASH': 2, 'APURIMAC': 3, 'AREQUIPA': 4,
    'AYACUCHO': 5, 'CAJAMARCA': 6, 'CALLAO': 7, 'CUSCO': 8,
    'HUANCAVELICA': 9, 'HUANUCO': 10, 'ICA': 11, 'JUNIN': 12,
    'LA LIBERTAD': 13, 'LAMBAYEQUE': 14, 'LIMA': 15, 'LORETO': 16,
    'MADRE DE DIOS': 17, 'MOQUEGUA': 18, 'PASCO': 19, 'PIURA': 20,
    'PUNO': 21, 'SAN MARTIN': 22, 'TACNA': 23, 'TUMBES': 24,
    'UCAYALI': 25
}

df_filtrado['DEPARTAMENTO'] = df_filtrado['DEPARTAMENTO'].replace(departamentos_numeros)
df_filtrado = df_filtrado.dropna(subset=['RUC_EMPLEADOR', 'TIPO_EMPRESA', 'MTO_TOTAL_DEMANDA', 'DEPARTAMENTO', 'ESTADO'])
df_filtrado['ESTADO'] = df_filtrado['ESTADO'].astype(int)

# Preparar los datos para el modelo
X = df_filtrado.drop('ESTADO', axis=1)
y = df_filtrado['ESTADO']
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)
scaler = StandardScaler()
X_train_scaled = scaler.fit_transform(X_train)
X_test_scaled = scaler.transform(X_test)
rf_model = RandomForestClassifier(n_estimators=100, random_state=42)
rf_model.fit(X_train_scaled, y_train)

# Definir la función de predicción
def predecir_retraso_pago(modelo, scaler, input_data):
    input_data_scaled = scaler.transform([input_data])
    prediction = modelo.predict(input_data_scaled)
    return prediction[0]

# Crear una ruta para la predicción
@app.route('/predict', methods=['POST'])
def predict():
    try:
        data = request.json
        input_data = [data['RUC_EMPLEADOR'], data['TIPO_EMPRESA'], data['MTO_TOTAL_DEMANDA'], data['DEPARTAMENTO']]
        resultado = predecir_retraso_pago(rf_model, scaler, input_data)
        estado = {
            1: "Retrasado",
            0: "No retrasado"
        }
        descripcion_estado = estado.get(resultado)
        return jsonify({'resultado': descripcion_estado})
    except Exception as e:
        return jsonify({'error': str(e)})

if __name__ == '__main__':
    app.run(debug=True)