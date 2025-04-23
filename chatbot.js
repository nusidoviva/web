let speakEnabled = false;
let chatStarted = false;

function toggleChat() {
  const window = document.getElementById('chatbot-window');
  const isVisible = window.style.display === 'flex';
  window.style.display = isVisible ? 'none' : 'flex';

  if (!isVisible && !chatStarted) {
    setTimeout(() => {
      const msg = "¡Jámea! Soy tu guía Emberá de Nusidó. ¿En qué puedo ayudarte hoy?";
      addBotMessage(msg);
      chatStarted = true;
    }, 300);
  }
}

function toggleSpeak() {
  speakEnabled = !speakEnabled;
  const btn = document.getElementById("speak-btn");
  const icon = document.getElementById("speak-icon");
  icon.textContent = speakEnabled ? "🔊" : "🔇";
  btn.setAttribute("aria-label", speakEnabled ? "Desactivar voz" : "Activar voz");
  
  if (!speakEnabled) {
    window.speechSynthesis.cancel();
  }
}

function sendMessage() {
  const input = document.getElementById('user-input');
  const message = input.value.trim();
  
  if (message === "") {
    input.focus();
    return;
  }
  
  addUserMessage(message);
  input.value = "";
  input.focus();
  
  // Mostrar indicador de que el bot está escribiendo
  showTypingIndicator();
  
  // Simular tiempo de respuesta
  setTimeout(() => {
    removeTypingIndicator();
    respondToMessage(message);
  }, 800 + Math.random() * 1000);
}

function sendSuggested(text) {
  addUserMessage(text);
  showTypingIndicator();
  
  setTimeout(() => {
    removeTypingIndicator();
    respondToMessage(text);
  }, 600);
}

function addUserMessage(message) {
  const chatLog = document.getElementById('chat-log');
  const div = document.createElement('div');
  div.className = 'user-msg';
  div.innerHTML = message;
  chatLog.insertBefore(div, document.getElementById('fixed-options'));
  chatLog.scrollTop = chatLog.scrollHeight;
}

function addBotMessage(message) {
  const chatLog = document.getElementById('chat-log');
  const div = document.createElement('div');
  div.className = 'bot-msg';
  div.innerHTML = message;
  chatLog.insertBefore(div, document.getElementById('fixed-options'));
  chatLog.scrollTop = chatLog.scrollHeight;
  
  if (speakEnabled) {
    speakText(message);
  }
}

function showTypingIndicator() {
  const chatLog = document.getElementById('chat-log');
  const typing = document.createElement('div');
  typing.id = 'typing-indicator';
  typing.className = 'bot-msg';
  typing.innerHTML = '<div class="typing-dots"><span></span><span></span><span></span></div>';
  chatLog.insertBefore(typing, document.getElementById('fixed-options'));
  chatLog.scrollTop = chatLog.scrollHeight;
}

function removeTypingIndicator() {
  const typing = document.getElementById('typing-indicator');
  if (typing) {
    typing.remove();
  }
}

function speakText(text) {
  // Cancelar cualquier habla previa
  window.speechSynthesis.cancel();
  
  const utter = new SpeechSynthesisUtterance(text);
  utter.lang = "es-ES";
  utter.rate = 0.9;
  utter.pitch = 1;
  
  // Filtrar solo el texto (eliminar posibles etiquetas HTML)
  utter.text = text.replace(/<[^>]*>/g, '');
  
  window.speechSynthesis.speak(utter);
}

function showHelp() {
  const helpMessage = `
    <strong>Guía de uso:</strong>
    <ul style="margin: 8px 0 0 0; padding-left: 20px;">
      <li>Pregunta sobre Nusidó, tours o cultura Emberá</li>
      <li>Usa los botones de preguntas frecuentes</li>
      <li>Haz clic en el icono 🔊 para activar voz</li>
      <li>Presiona Enter para enviar mensajes</li>
    </ul>
  `;
  addBotMessage(helpMessage);
}

function respondToMessage(message) {
  const lower = message.toLowerCase();
  let response = "¡Jámea! Gracias por tu interés en nuestra cultura. ¿En qué más te puedo ayudar?";
  
  // Mejores expresiones regulares para coincidencias
  const greetings = /hola|buenas|saludos|jámea|hello|hi/i;
  const location = /dónde|ubicación|nusidó|como llegar/i;
  const tour = /tour|actividad|incluye|qué hacer|programa/i;
  const paint = /pintura|diseños|facial|corporal/i;
  const food = /comida|alimentación|plato|bebida|típico/i;
  const book = /reservar|reserva|agendar|cómo visitar|visitar/i;
  
  if (greetings.test(lower)) {
    response = "¡Jámea! Bienvenido/a al mundo Emberá. 🌿 Somos guardianes de la selva y nuestra cultura. ¿Qué te gustaría saber?";
  } else if (location.test(lower)) {
    response = "Nusidó es una comunidad Emberá ubicada en el corazón de Antioquia, Colombia. Rodeada de ríos cristalinos y selva exuberante. 🗺️";
  } else if (tour.test(lower)) {
    response = "Nuestro tour cultural incluye:<br>- Danzas tradicionales<br>- Pintura facial con jagua<br>- Caminata ecológica<br>- Demostración de artesanías<br>- Almuerzo típico<br>- Charla sobre nuestra cosmovisión";
  } else if (paint.test(lower)) {
    response = "La pintura con jagua es sagrada para nosotros. Cada diseño cuenta una historia y ofrece protección espiritual. Los patrones representan elementos de la naturaleza como serpientes, plantas y ríos.";
  } else if (food.test(lower)) {
    response = "Ofrecemos comida tradicional preparada con ingredientes de nuestra tierra:<br>- Pescado fresco envuelto en hojas<br>- Plátano maduro<br>- Yuca<br>- Frutas tropicales<br>- Bebidas ancestrales";
  } else if (book.test(lower)) {
    response = "Puedes reservar tu experiencia cultural directamente haciendo clic en el botón 'Reservar' o contactándonos por WhatsApp. ¡Te guiaremos en este viaje inolvidable!";
  }
  
  addBotMessage(response);
}

// Permitir enviar mensaje con Enter
document.getElementById('user-input').addEventListener('keypress', function(e) {
  if (e.key === 'Enter') {
    sendMessage();
  }
});

// Animación para los puntos de typing
document.head.insertAdjacentHTML('beforeend', `
  <style>
    .typing-dots {
      display: inline-flex;
      align-items: center;
      height: 20px;
    }
    .typing-dots span {
      width: 8px;
      height: 8px;
      margin: 0 2px;
      background-color: var(--text-light);
      border-radius: 50%;
      display: inline-block;
      animation: typingAnimation 1.4s infinite ease-in-out;
    }
    .typing-dots span:nth-child(1) {
      animation-delay: 0s;
    }
    .typing-dots span:nth-child(2) {
      animation-delay: 0.2s;
    }
    .typing-dots span:nth-child(3) {
      animation-delay: 0.4s;
    }
    @keyframes typingAnimation {
      0%, 60%, 100% { transform: translateY(0); }
      30% { transform: translateY(-5px); }
    }
  </style>
`);