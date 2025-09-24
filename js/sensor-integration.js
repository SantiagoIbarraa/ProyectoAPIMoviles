/**
 * Integración con sensor MAX30100
 * Este módulo maneja la conexión con el sensor de frecuencia cardíaca
 * y reemplaza la simulación de PPM con datos reales
 */

class SensorIntegration {
    constructor() {
        this.sensorConnected = false;
        this.sensorData = {
            heartRate: 0,
            spO2: 0,
            timestamp: 0
        };
        this.updateInterval = null;
        this.retryCount = 0;
        this.maxRetries = 5;
        this.retryDelay = 2000; // 2 segundos
        
        // Elementos del DOM
        this.ppmSlider = document.getElementById('ppmSlider');
        this.ppmValue = document.getElementById('ppmValue');
        this.startButton = document.getElementById('startSimulation');
        this.recommendationsContainer = document.getElementById('songRecommendations');
        
        // Estado de la simulación
        this.isSimulating = false;
        this.simulationInterval = null;
        
        this.init();
    }
    
    init() {
        console.log('Inicializando integración con sensor MAX30100...');
        this.updateButtonText();
        this.setupEventListeners();
        this.startSensorConnection();
    }
    
    setupEventListeners() {
        if (this.startButton) {
            this.startButton.addEventListener('click', () => {
                if (!this.isSimulating) {
                    this.startSensorMode();
                } else {
                    this.stopSensorMode();
                }
            });
        }
        
        // Mantener funcionalidad del slider para modo manual
        if (this.ppmSlider) {
            this.ppmSlider.addEventListener('input', (e) => {
                if (!this.isSimulating) {
                    this.updatePPMDisplay(e.target.value);
                    this.updateRecommendations(e.target.value);
                }
            });
        }
    }
    
    async startSensorConnection() {
        try {
            console.log('Conectando con sensor MAX30100...');
            
            // Intentar obtener datos del sensor
            const response = await fetch('sensor_data.php', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                if (data.success && data.data.heartRate > 0) {
                    this.sensorConnected = true;
                    this.sensorData = data.data;
                    console.log('Sensor conectado exitosamente:', this.sensorData);
                    this.updateButtonText();
                    this.showSensorStatus('Sensor conectado', 'success');
                } else {
                    throw new Error('No hay datos válidos del sensor');
                }
            } else {
                throw new Error(`Error HTTP: ${response.status}`);
            }
        } catch (error) {
            console.warn('Error conectando con sensor:', error.message);
            this.sensorConnected = false;
            this.updateButtonText();
            this.showSensorStatus('Sensor no disponible - Modo manual', 'warning');
            this.scheduleRetry();
        }
    }
    
    scheduleRetry() {
        if (this.retryCount < this.maxRetries) {
            this.retryCount++;
            console.log(`Reintentando conexión en ${this.retryDelay/1000} segundos... (${this.retryCount}/${this.maxRetries})`);
            setTimeout(() => {
                this.startSensorConnection();
            }, this.retryDelay);
        } else {
            console.log('Máximo número de reintentos alcanzado. Usando modo manual.');
            this.showSensorStatus('Sensor no disponible - Usando modo manual', 'error');
        }
    }
    
    startSensorMode() {
        if (!this.sensorConnected) {
            this.showSensorStatus('Sensor no disponible. Iniciando modo manual...', 'warning');
            this.startManualSimulation();
            return;
        }
        
        console.log('Iniciando modo sensor...');
        this.isSimulating = true;
        this.updateButtonText();
        
        // Actualizar datos del sensor cada segundo
        this.updateInterval = setInterval(async () => {
            await this.updateSensorData();
        }, 1000);
        
        // Iniciar reproducción automática
        if (typeof startAutoPlaySongs === 'function') {
            startAutoPlaySongs();
        }
        
        this.showSensorStatus('Modo sensor activo', 'success');
    }
    
    stopSensorMode() {
        console.log('Deteniendo modo sensor...');
        this.isSimulating = false;
        
        if (this.updateInterval) {
            clearInterval(this.updateInterval);
            this.updateInterval = null;
        }
        
        if (this.simulationInterval) {
            clearInterval(this.simulationInterval);
            this.simulationInterval = null;
        }
        
        // Detener reproducción automática
        if (typeof stopAutoPlaySongs === 'function') {
            stopAutoPlaySongs();
        }
        
        if (typeof currentAudio !== 'undefined' && currentAudio) {
            currentAudio.pause();
            currentAudio.currentTime = 0;
        }
        
        if (typeof showStopButton === 'function') {
            showStopButton(false);
        }
        
        this.updateButtonText();
        this.showSensorStatus('Modo sensor detenido', 'info');
    }
    
    async updateSensorData() {
        try {
            const response = await fetch('sensor_data.php', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                if (data.success && data.data.heartRate > 0) {
                    this.sensorData = data.data;
                    this.updatePPMDisplay(this.sensorData.heartRate);
                    this.updateRecommendations(this.sensorData.heartRate);
                    
                    // Actualizar slider si existe
                    if (this.ppmSlider) {
                        this.ppmSlider.value = Math.round(this.sensorData.heartRate);
                    }
                    
                    console.log(`Datos del sensor: HR=${this.sensorData.heartRate}, SpO2=${this.sensorData.spO2}`);
                }
            }
        } catch (error) {
            console.warn('Error actualizando datos del sensor:', error.message);
            // Si falla la conexión, cambiar a modo manual
            this.sensorConnected = false;
            this.stopSensorMode();
            this.startManualSimulation();
        }
    }
    
    startManualSimulation() {
        console.log('Iniciando simulación manual...');
        this.isSimulating = true;
        this.updateButtonText();
        
        let direction = 1;
        let currentPPM = parseInt(this.ppmSlider.value);
        let intensityFactor = 1;
        
        this.simulationInterval = setInterval(() => {
            // Simular variaciones naturales en el PPM para corredores
            const baseVariation = Math.random() * 5;
            const variation = baseVariation * intensityFactor;
            currentPPM += variation * direction;
            
            // Cambiar dirección y ajustar intensidad ocasionalmente
            if (Math.random() < 0.15) {
                direction *= -1;
                intensityFactor = 0.8 + Math.random() * 0.4;
            }
            
            // Mantener PPM dentro del rango para corredores (100-180)
            currentPPM = Math.min(Math.max(currentPPM, 100), 180);
            
            this.ppmSlider.value = Math.round(currentPPM);
            this.updatePPMDisplay(currentPPM);
            this.updateRecommendations(currentPPM);
        }, 1000);
        
        // Iniciar reproducción automática
        if (typeof startAutoPlaySongs === 'function') {
            startAutoPlaySongs();
        }
        
        this.showSensorStatus('Modo simulación manual activo', 'warning');
    }
    
    updatePPMDisplay(value) {
        if (this.ppmValue) {
            this.ppmValue.textContent = Math.round(value);
        }
    }
    
    updateRecommendations(currentPPM) {
        if (typeof updateRecommendations === 'function') {
            updateRecommendations(currentPPM);
        }
    }
    
    updateButtonText() {
        if (this.startButton) {
            if (this.isSimulating) {
                this.startButton.textContent = this.sensorConnected ? 'Detener Sensor' : 'Detener Simulación';
            } else {
                this.startButton.textContent = this.sensorConnected ? 'Iniciar Sensor' : 'Iniciar Simulación';
            }
        }
    }
    
    showSensorStatus(message, type = 'info') {
        // Crear o actualizar elemento de estado
        let statusElement = document.getElementById('sensorStatus');
        if (!statusElement) {
            statusElement = document.createElement('div');
            statusElement.id = 'sensorStatus';
            statusElement.className = 'alert alert-info mt-3';
            statusElement.style.position = 'fixed';
            statusElement.style.top = '10px';
            statusElement.style.right = '10px';
            statusElement.style.zIndex = '9999';
            statusElement.style.minWidth = '300px';
            document.body.appendChild(statusElement);
        }
        
        // Actualizar contenido y estilo
        statusElement.textContent = message;
        statusElement.className = `alert alert-${type === 'success' ? 'success' : type === 'warning' ? 'warning' : type === 'error' ? 'danger' : 'info'} mt-3`;
        
        // Auto-ocultar después de 3 segundos (excepto para errores)
        if (type !== 'error') {
            setTimeout(() => {
                if (statusElement && statusElement.parentNode) {
                    statusElement.parentNode.removeChild(statusElement);
                }
            }, 3000);
        }
    }
    
    // Método para enviar datos del sensor (llamado desde Arduino)
    async sendSensorData(heartRate, spO2) {
        try {
            const response = await fetch('sensor_data.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    heartRate: heartRate,
                    spO2: spO2,
                    timestamp: Date.now()
                })
            });
            
            if (response.ok) {
                const data = await response.json();
                console.log('Datos enviados exitosamente:', data);
                return true;
            } else {
                console.error('Error enviando datos:', response.status);
                return false;
            }
        } catch (error) {
            console.error('Error enviando datos del sensor:', error);
            return false;
        }
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    window.sensorIntegration = new SensorIntegration();
});

// Exportar para uso global
window.SensorIntegration = SensorIntegration;
