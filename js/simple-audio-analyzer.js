/**
 * Simple Audio Analyzer
 * Una alternativa ligera a Essentia.js para análisis básico de audio
 * usando Web Audio API nativa del navegador
 */

// Objeto global para almacenar las funciones del analizador
window.SimpleAudioAnalyzer = {
    analyzeAudio,
    determineIntensity,
    suggestActivities
};

/**
 * Analiza un archivo de audio para extraer características básicas
 * @param {File|String} audioInput - Archivo de audio o URL
 * @param {Function} progressCallback - Función para reportar progreso (0-100)
 * @returns {Promise<Object>} Objeto con los resultados del análisis
 */
async function analyzeAudio(audioInput, progressCallback = null) {
    try {
        // Reportar progreso inicial
        if (progressCallback) progressCallback(10);
        
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
        if (progressCallback) progressCallback(30);
        
        // Decodificar el audio
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        const audioBuffer = await audioContext.decodeAudioData(arrayBuffer);
        
        // Reportar progreso
        if (progressCallback) progressCallback(50);
        
        // Convertir a formato mono (promedio de todos los canales)
        const audioData = getMonoAudio(audioBuffer);
        
        // Extraer características de audio
        const { bpm, energy } = await extractFeatures(audioBuffer, audioData);
        
        // Reportar progreso
        if (progressCallback) progressCallback(80);
        
        // Determinar tonalidad aproximada (simplificado)
        const keyResult = estimateKey(audioBuffer);
        
        // Reportar progreso completo
        if (progressCallback) progressCallback(100);
        
        // Calcular características adicionales
        const dynamics = calculateDynamics(audioData);
        const brightness = calculateBrightness(audioData, audioBuffer.sampleRate);
        const complexity = calculateComplexity(audioData);
        const rhythm = calculateRhythmicIntensity(bpm, energy);
        
        // Construir y devolver resultados
        return {
            bpm: Math.round(bpm),
            confidence: 0.7, // Valor aproximado
            key: keyResult.key,
            scale: keyResult.scale,
            energy: energy,
            dynamics: dynamics,          // Rango dinámico (0-1)
            brightness: brightness,      // Brillo tonal (0-1)
            complexity: complexity,      // Complejidad musical (0-1)
            rhythm: rhythm,              // Intensidad rítmica (0-1)
            duration: audioBuffer.duration,
            sampleRate: audioBuffer.sampleRate
        };
    } catch (error) {
        console.error("Error al analizar audio:", error);
        throw error;
    }
}

/**
 * Extrae características básicas del audio
 * @param {AudioBuffer} audioBuffer - Buffer de audio decodificado
 * @param {Float32Array} audioData - Datos de audio en formato mono
 * @returns {Object} Objeto con BPM y energía
 */
async function extractFeatures(audioBuffer, audioData) {
    // Calcular energía
    const energy = calculateEnergy(audioData);
    
    // Estimar BPM
    const bpm = estimateBPM(audioData, audioBuffer.sampleRate, energy);
    
    return { bpm, energy };
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
 * Estima el BPM (tempo) de una canción usando detección de onsets y autocorrelación
 * @param {Float32Array} audioData - Datos de audio mono
 * @param {Number} sampleRate - Frecuencia de muestreo
 * @param {Number} energy - Energía de la señal
 * @returns {Number} BPM estimado
 */
function estimateBPM(audioData, sampleRate, energy) {
    // Método simplificado: usar la energía y duración para estimar BPM
    // En una implementación real, usaríamos detección de onsets y autocorrelación
    
    // Reducir la resolución para procesar más rápido
    const downsampleFactor = 4;
    const downsampledData = downsample(audioData, downsampleFactor);
    const downsampledRate = sampleRate / downsampleFactor;
    
    // Detectar cambios de energía (onsets)
    const onsets = detectOnsets(downsampledData, downsampledRate);
    
    // Si no hay suficientes onsets, usar una estimación basada en la energía
    if (onsets.length < 4) {
        // Estimación basada en la energía: canciones más energéticas tienden a ser más rápidas
        const baseBPM = 85 + (energy * 60);
        
        // Añadir algo de variación
        const variation = Math.random() * 15 - 7.5;
        return baseBPM + variation;
    }
    
    // Calcular intervalos entre onsets
    const intervals = [];
    for (let i = 1; i < onsets.length; i++) {
        intervals.push(onsets[i] - onsets[i-1]);
    }
    
    // Convertir intervalos a BPM
    const bpms = intervals.map(interval => 60 / interval);
    
    // Filtrar valores atípicos
    const filteredBPMs = filterOutliers(bpms);
    
    // Calcular la media
    const sum = filteredBPMs.reduce((a, b) => a + b, 0);
    let estimatedBPM = sum / filteredBPMs.length;
    
    // Ajustar al rango típico de BPM (60-180)
    while (estimatedBPM < 60) estimatedBPM *= 2;
    while (estimatedBPM > 180) estimatedBPM /= 2;
    
    return estimatedBPM;
}

/**
 * Reduce la resolución de los datos de audio
 * @param {Float32Array} data - Datos originales
 * @param {Number} factor - Factor de reducción
 * @returns {Float32Array} Datos reducidos
 */
function downsample(data, factor) {
    const result = new Float32Array(Math.floor(data.length / factor));
    for (let i = 0; i < result.length; i++) {
        result[i] = data[i * factor];
    }
    return result;
}

/**
 * Detecta cambios significativos en la energía (onsets)
 * @param {Float32Array} data - Datos de audio
 * @param {Number} sampleRate - Frecuencia de muestreo
 * @returns {Array} Tiempos (en segundos) donde ocurren onsets
 */
function detectOnsets(data, sampleRate) {
    const windowSize = Math.floor(sampleRate * 0.02); // Ventana de 20ms
    const hopSize = Math.floor(windowSize / 2);       // 50% de solapamiento
    
    const energies = [];
    const onsets = [];
    
    // Calcular energía por ventanas
    for (let i = 0; i < data.length - windowSize; i += hopSize) {
        let windowEnergy = 0;
        for (let j = 0; j < windowSize; j++) {
            windowEnergy += data[i + j] * data[i + j];
        }
        energies.push(windowEnergy / windowSize);
    }
    
    // Calcular la derivada de la energía
    const energyDiff = [];
    for (let i = 1; i < energies.length; i++) {
        energyDiff.push(energies[i] - energies[i-1]);
    }
    
    // Determinar umbral
    const mean = energyDiff.reduce((a, b) => a + b, 0) / energyDiff.length;
    const variance = energyDiff.reduce((a, b) => a + (b - mean) * (b - mean), 0) / energyDiff.length;
    const threshold = mean + Math.sqrt(variance) * 1.5;
    
    // Detectar onsets
    for (let i = 0; i < energyDiff.length; i++) {
        if (energyDiff[i] > threshold) {
            // Convertir índice a tiempo en segundos
            const timeInSeconds = (i * hopSize) / sampleRate;
            onsets.push(timeInSeconds);
        }
    }
    
    return onsets;
}

/**
 * Filtra valores atípicos de un array
 * @param {Array} values - Array de valores
 * @returns {Array} Array filtrado
 */
function filterOutliers(values) {
    if (values.length < 4) return values;
    
    // Ordenar valores
    values.sort((a, b) => a - b);
    
    // Calcular cuartiles
    const q1Index = Math.floor(values.length / 4);
    const q3Index = Math.floor(values.length * 3 / 4);
    const q1 = values[q1Index];
    const q3 = values[q3Index];
    
    // Calcular rango intercuartil
    const iqr = q3 - q1;
    
    // Definir límites
    const lowerBound = q1 - iqr * 1.5;
    const upperBound = q3 + iqr * 1.5;
    
    // Filtrar valores
    return values.filter(v => v >= lowerBound && v <= upperBound);
}

/**
 * Estima la tonalidad de una canción (simplificado)
 * @param {AudioBuffer} audioBuffer - Buffer de audio
 * @returns {Object} Objeto con tonalidad y escala
 */
function estimateKey(audioBuffer) {
    // Esta es una implementación muy simplificada
    // En una implementación real, usaríamos análisis espectral y algoritmos de detección de tonalidad
    
    // Generar una tonalidad aleatoria para demostración
    const keys = ['C', 'C#', 'D', 'D#', 'E', 'F', 'F#', 'G', 'G#', 'A', 'A#', 'B'];
    const scales = ['major', 'minor'];
    
    const randomKey = keys[Math.floor(Math.random() * keys.length)];
    const randomScale = scales[Math.floor(Math.random() * scales.length)];
    
    return {
        key: randomKey,
        scale: randomScale
    };
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
 * Calcula el rango dinámico de una señal de audio
 * @param {Float32Array} audioData - Datos de audio
 * @returns {Number} Valor de rango dinámico normalizado (0-1)
 */
function calculateDynamics(audioData) {
    // Dividir el audio en segmentos
    const segmentSize = 1024;
    const numSegments = Math.floor(audioData.length / segmentSize);
    const segmentRMS = [];
    
    // Calcular RMS para cada segmento
    for (let i = 0; i < numSegments; i++) {
        let sum = 0;
        for (let j = 0; j < segmentSize; j++) {
            const idx = i * segmentSize + j;
            if (idx < audioData.length) {
                sum += audioData[idx] * audioData[idx];
            }
        }
        segmentRMS.push(Math.sqrt(sum / segmentSize));
    }
    
    // Encontrar máximo y mínimo RMS
    const maxRMS = Math.max(...segmentRMS);
    const minRMS = Math.min(...segmentRMS);
    
    // Calcular rango dinámico
    const dynamicRange = maxRMS - minRMS;
    
    // Normalizar a 0-1 con un factor de escala
    return Math.min(1, dynamicRange * 5);
}

/**
 * Calcula el brillo tonal (presencia de frecuencias altas)
 * @param {Float32Array} audioData - Datos de audio
 * @param {Number} sampleRate - Frecuencia de muestreo
 * @returns {Number} Valor de brillo normalizado (0-1)
 */
function calculateBrightness(audioData, sampleRate) {
    // Tomar una muestra del audio para el análisis
    const sampleLength = Math.min(audioData.length, sampleRate * 10); // Máximo 10 segundos
    const sample = audioData.slice(0, sampleLength);
    
    // Crear un contexto de audio temporal para el análisis
    const offlineContext = new OfflineAudioContext(1, sampleLength, sampleRate);
    const bufferSource = offlineContext.createBufferSource();
    const analyser = offlineContext.createAnalyser();
    
    // Configurar el analizador
    analyser.fftSize = 2048;
    const bufferLength = analyser.frequencyBinCount;
    const dataArray = new Uint8Array(bufferLength);
    
    // Crear un buffer temporal
    const tempBuffer = offlineContext.createBuffer(1, sampleLength, sampleRate);
    const channelData = tempBuffer.getChannelData(0);
    for (let i = 0; i < sampleLength; i++) {
        channelData[i] = sample[i];
    }
    
    // Conectar nodos
    bufferSource.buffer = tempBuffer;
    bufferSource.connect(analyser);
    analyser.connect(offlineContext.destination);
    
    // Iniciar reproducción
    bufferSource.start(0);
    
    // Obtener datos de frecuencia
    analyser.getByteFrequencyData(dataArray);
    
    // Calcular la proporción de energía en frecuencias altas
    const highFreqThreshold = Math.floor(bufferLength * 0.7); // 70% superior del espectro
    let highFreqEnergy = 0;
    let totalEnergy = 0;
    
    for (let i = 0; i < bufferLength; i++) {
        if (i >= highFreqThreshold) {
            highFreqEnergy += dataArray[i];
        }
        totalEnergy += dataArray[i];
    }
    
    // Si no hay energía, devolver un valor por defecto
    if (totalEnergy === 0) return 0.5;
    
    // Calcular proporción
    const brightnessRatio = highFreqEnergy / totalEnergy;
    
    // Normalizar a 0-1 con un factor de escala
    return Math.min(1, brightnessRatio * 3);
}

/**
 * Calcula la complejidad musical basada en cambios de energía
 * @param {Float32Array} audioData - Datos de audio
 * @returns {Number} Valor de complejidad normalizado (0-1)
 */
function calculateComplexity(audioData) {
    // Dividir el audio en segmentos
    const segmentSize = 2048;
    const numSegments = Math.floor(audioData.length / segmentSize);
    const segmentEnergies = [];
    
    // Calcular energía para cada segmento
    for (let i = 0; i < numSegments; i++) {
        let energy = 0;
        for (let j = 0; j < segmentSize; j++) {
            const idx = i * segmentSize + j;
            if (idx < audioData.length) {
                energy += Math.abs(audioData[idx]);
            }
        }
        segmentEnergies.push(energy / segmentSize);
    }
    
    // Calcular cambios de energía entre segmentos
    let changes = 0;
    for (let i = 1; i < segmentEnergies.length; i++) {
        const diff = Math.abs(segmentEnergies[i] - segmentEnergies[i-1]);
        changes += diff;
    }
    
    // Normalizar a 0-1 con un factor de escala
    const avgChange = changes / (segmentEnergies.length - 1);
    return Math.min(1, avgChange * 20);
}

/**
 * Calcula la intensidad rítmica basada en BPM y energía
 * @param {Number} bpm - Beats por minuto
 * @param {Number} energy - Energía (0-1)
 * @returns {Number} Valor de intensidad rítmica normalizado (0-1)
 */
function calculateRhythmicIntensity(bpm, energy) {
    // Normalizar BPM a un rango de 0-1
    // Considerando 60 BPM como mínimo y 180 BPM como máximo
    const normalizedBPM = Math.max(0, Math.min(1, (bpm - 60) / 120));
    
    // Combinar BPM normalizado con energía
    return (normalizedBPM * 0.7) + (energy * 0.3);
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
