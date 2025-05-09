/**
 * Audio Analyzer con Essentia.js
 * Este módulo proporciona funcionalidades para analizar archivos de audio
 * y extraer características como BPM, tonalidad, y más.
 */

// Variable global para almacenar la instancia de Essentia
let essentiaInstance = null;

/**
 * Inicializa Essentia.js
 * @returns {Promise} Promesa que se resuelve cuando Essentia está inicializada
 */
async function initEssentia() {
    if (essentiaInstance) {
        return essentiaInstance;
    }
    
    try {
        // Verificar que EssentiaWASM esté disponible
        if (typeof EssentiaWASM !== 'function') {
            throw new Error('EssentiaWASM no está disponible. Asegúrate de que essentia-wasm.web.js esté cargado correctamente.');
        }
        
        // Inicializar Essentia WASM
        const essentia = await EssentiaWASM();
        const EssentiaModule = essentia.EssentiaModule;
        essentiaInstance = {
            module: EssentiaModule,
            essentia: new essentia.Essentia(EssentiaModule),
            algorithms: {}
        };
        
        console.log("Essentia.js inicializada correctamente");
        return essentiaInstance;
    } catch (error) {
        console.error("Error al inicializar Essentia.js:", error);
        throw error;
    }
}

/**
 * Analiza un archivo de audio para extraer su BPM
 * @param {File|String} audioInput - Archivo de audio o URL
 * @param {Function} progressCallback - Función para reportar progreso (0-100)
 * @returns {Promise<Object>} Objeto con los resultados del análisis
 */
async function analyzeAudio(audioInput, progressCallback = null) {
    try {
        // Reportar progreso inicial
        if (progressCallback) progressCallback(10);
        
        // Inicializar Essentia si no está inicializada
        const essentiaObj = await initEssentia();
        const essentia = essentiaObj.essentia;
        
        // Reportar progreso
        if (progressCallback) progressCallback(20);
        
        // Obtener ArrayBuffer del archivo de audio
        let arrayBuffer;
        if (typeof audioInput === 'string') {
            // Si es una URL
            const response = await fetch(audioInput);
            arrayBuffer = await response.arrayBuffer();
        } else {
            // Si es un objeto File
            arrayBuffer = await audioInput.arrayBuffer();
        }
        
        // Reportar progreso
        if (progressCallback) progressCallback(40);
        
        // Decodificar el audio
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const audioBuffer = await audioContext.decodeAudioData(arrayBuffer);
        
        // Reportar progreso
        if (progressCallback) progressCallback(60);
        
        // Convertir a formato mono (promedio de todos los canales)
        const audioData = getMonoAudio(audioBuffer);
        
        // Reportar progreso
        if (progressCallback) progressCallback(70);
        
        // Usar el método directo de Essentia para calcular BPM
        // Convertir el array de JavaScript a un vector de Essentia
        const vectorInput = essentia.arrayToVector(audioData);
        
        // Calcular el BPM usando PercivalBpmEstimator (más simple y estable que RhythmExtractor)
        const bpmResult = essentia.PercivalBpmEstimator(vectorInput);
        const bpm = bpmResult.bpm;
        
        // Reportar progreso
        if (progressCallback) progressCallback(80);
        
        // Calcular la tonalidad usando el algoritmo Key
        const keyResult = essentia.KeyExtractor(vectorInput, audioBuffer.sampleRate);
        
        // Reportar progreso
        if (progressCallback) progressCallback(90);
        
        // Analizar energía y otros parámetros
        const energy = calculateEnergy(audioData);
        
        // Reportar progreso completo
        if (progressCallback) progressCallback(100);
        
        // Construir y devolver resultados
        return {
            bpm: Math.round(bpm * 10) / 10, // Redondear a 1 decimal
            confidence: 0.8, // Valor por defecto ya que PercivalBpmEstimator no devuelve confianza
            key: keyResult.key,
            scale: keyResult.scale,
            energy: energy,
            duration: audioBuffer.duration,
            sampleRate: audioBuffer.sampleRate
        };
    } catch (error) {
        console.error("Error al analizar audio:", error);
        throw error;
    }
}

/**
 * Convierte un AudioBuffer a un array mono (promedio de todos los canales)
 * @param {AudioBuffer} audioBuffer - Buffer de audio a convertir
 * @returns {Float32Array} Array de audio mono
 */
function getMonoAudio(audioBuffer) {
    const numChannels = audioBuffer.numberOfChannels;
    const length = audioBuffer.length;
    const monoData = new Float32Array(length);
    
    // Si solo hay un canal, devolver ese canal directamente
    if (numChannels === 1) {
        return audioBuffer.getChannelData(0);
    }
    
    // Promediar todos los canales
    for (let i = 0; i < numChannels; i++) {
        const channelData = audioBuffer.getChannelData(i);
        for (let j = 0; j < length; j++) {
            monoData[j] += channelData[j] / numChannels;
        }
    }
    
    return monoData;
}

/**
 * Calcula la energía promedio de una señal de audio
 * @param {Float32Array} audioData - Datos de audio
 * @returns {Number} Valor de energía normalizado (0-1)
 */
function calculateEnergy(audioData) {
    let energySum = 0;
    
    for (let i = 0; i < audioData.length; i++) {
        energySum += audioData[i] * audioData[i];
    }
    
    const avgEnergy = energySum / audioData.length;
    
    // Normalizar a un rango de 0-1 (aproximadamente)
    return Math.min(1, Math.sqrt(avgEnergy) * 5);
}

/**
 * Determina la intensidad de una canción basada en BPM y energía
 * @param {Number} bpm - Beats por minuto
 * @param {Number} energy - Energía (0-1)
 * @returns {String} Categoría de intensidad: 'baja', 'media', 'alta'
 */
function determineIntensity(bpm, energy) {
    // Combinar BPM y energía para determinar intensidad
    const combinedScore = (bpm / 200) * 0.7 + energy * 0.3;
    
    if (combinedScore < 0.4) return 'baja';
    if (combinedScore < 0.7) return 'media';
    return 'alta';
}

/**
 * Sugiere actividades basadas en el BPM y la energía de la canción
 * @param {Number} bpm - Beats por minuto
 * @param {Number} energy - Energía (0-1)
 * @returns {Array<String>} Lista de actividades sugeridas
 */
function suggestActivities(bpm, energy) {
    const intensity = determineIntensity(bpm, energy);
    
    const activities = {
        baja: [
            'Meditación',
            'Yoga',
            'Relajación',
            'Lectura',
            'Descanso'
        ],
        media: [
            'Caminata',
            'Ciclismo ligero',
            'Baile suave',
            'Trabajo',
            'Estudio'
        ],
        alta: [
            'Correr',
            'Entrenamiento intenso',
            'Baile enérgico',
            'Cardio',
            'Fiesta'
        ]
    };
    
    return activities[intensity];
}

// Exportar funciones para uso global
window.AudioAnalyzer = {
    analyzeAudio,
    determineIntensity,
    suggestActivities
};
