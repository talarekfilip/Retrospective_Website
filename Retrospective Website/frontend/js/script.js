document.addEventListener("DOMContentLoaded", function() {
    // Check if debug mode is active
    const debugMode = true;
    
    if (debugMode) {
        console.log("APP STARTED IN DEBUG MODE");
        console.log("Checking initial reCAPTCHA status:");
        console.log("- grecaptcha defined:", typeof grecaptcha !== 'undefined');
        console.log("- reCAPTCHA container exists:", !!document.querySelector("#recaptcha-container"));
    }
    
    // Initialize loading screen
    const loadingScreen = document.getElementById("loading-screen");
    
    // Initialize audio
    const audio = document.getElementById("background-music");
    if (audio) {
        audio.volume = 0.2;
        
        // Try to play music from saved position
        const savedTime = localStorage.getItem("musicTime");
        if (savedTime) {
            audio.currentTime = parseFloat(savedTime);
        }
        
        // Handle playback errors
        audio.onerror = function() {
            console.error("Error loading audio file");
        };
        
        // Try automatic playback
        const playPromise = audio.play();
        if (playPromise !== undefined) {
            playPromise.catch(function(error) {
                console.error("Automatic playback blocked:", error);
            });
        }
    }

    // Create stars
    const sky = document.querySelector(".sky");
    if (sky) {
        function createStar() {
            const star = document.createElement("div");
            star.classList.add("star");
            const size = Math.random() * 3 + 1;
            star.style.width = `${size}px`;
            star.style.height = `${size}px`;
            star.style.left = `${Math.random() * 100}vw`;
            star.style.top = `-${size}px`;
            star.style.animationDuration = `${Math.random() * 3 + 2}s`;
            star.style.animationDelay = `${Math.random()}s`;
            sky.appendChild(star);
            setTimeout(() => star.remove(), 5000);
        }

        setInterval(createStar, 100);
    }
    
    // Check if reCAPTCHA is loaded correctly
    if (typeof grecaptcha === 'undefined') {
        console.warn('reCAPTCHA not loaded yet');
        
        // Add manual loader to ensure reCAPTCHA is available
        window.onRecaptchaLoaded = function() {
            console.log('reCAPTCHA loaded manually');
        };
        
        // Try to reinsert script
        if (!document.getElementById('recaptcha-script')) {
            const script = document.createElement('script');
            script.id = 'recaptcha-script';
            script.src = 'https://www.google.com/recaptcha/api.js?onload=onRecaptchaLoaded&render=explicit';
            script.async = true;
            script.defer = true;
            document.head.appendChild(script);
        }
    } else {
        console.log('reCAPTCHA already loaded');
    }
    
    // Hide loading screen after all content loads
    window.addEventListener('load', function() {
        setTimeout(function() {
            if (loadingScreen) {
                loadingScreen.style.opacity = '0';
                setTimeout(function() {
                    loadingScreen.style.display = 'none';
                }, 500);
            }
        }, 1500);
    });

    // Smooth scrolling for navigation links
    const navLinks = document.querySelectorAll('a[href^="#"]');
    for (const link of navLinks) {
        link.addEventListener('click', function(e) {
            if (this.getAttribute('href') !== '#') {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    }

    // Odblokowuje link wizualnie po wpisaniu nicku, ale nick musi być zweryfikowany przez serwer
    const nicknameInput = document.getElementById('nickname');
    const discordInvite = document.getElementById('discord-invite');
    
    if (nicknameInput) {
        nicknameInput.addEventListener('input', function() {
            const submitBtn = document.querySelector('.submit-btn');
            const nickname = this.value.trim();
            
            // Sprawdzenie podstawowej walidacji nicku
            const isValidFormat = /^[a-zA-Z0-9_]+$/.test(nickname);
            const isValidLength = nickname.length >= 3;
            
            if (isValidLength && isValidFormat) {
                submitBtn.classList.add('nickname-ready');
                
                // Nie odblokowujemy linka Discorda - będzie odblokowany dopiero po walidacji serwerowej
            } else {
                submitBtn.classList.remove('nickname-ready');
            }
        });
    }
});

// Save music playback time before page close
window.addEventListener("beforeunload", function() {
    const audio = document.getElementById("background-music");
    if (audio) {
        localStorage.setItem("musicTime", audio.currentTime);
    }
});

// Scroll to guild-advert section
function scrollToGuildAdvert() {
    const element = document.getElementById('guild-advert');
    if (element) {
        element.scrollIntoView({
            behavior: 'smooth',
            block: 'center'
        });
    }
}

// Handle form submission
function submitNickname(event) {
    event.preventDefault();
    console.log("Form submission started");
    
    // Get form data
    const nickname = document.getElementById('nickname').value.trim();
    const formMessage = document.getElementById('form-message');
    const discordInvite = document.getElementById('discord-invite');
    
    // Display form message to provide feedback
    const showMessage = (message, isError = false) => {
        formMessage.textContent = message;
        formMessage.className = isError ? 'form-message error' : 'form-message success';
        formMessage.style.display = 'block';
        console.log(`Message displayed: ${message} (isError: ${isError})`);
    };
    
    // Validate nickname
    if (!nickname) {
        showMessage('Please enter your ROBLOX nickname', true);
        return false;
    }
    
    // Minimum length validation
    if (nickname.length < 3) {
        showMessage('Nickname must be at least 3 characters long', true);
        return false;
    }
    
    // Sprawdzenie, czy zawiera tylko dozwolone znaki
    if (!/^[a-zA-Z0-9_]+$/.test(nickname)) {
        showMessage('Nickname can only contain letters, numbers and underscores', true);
        return false;
    }
    
    // Disable submit button to prevent multiple submissions
    const submitBtn = document.querySelector('.submit-btn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span>Processing...</span> <i class="fas fa-spinner fa-spin"></i>';
    
    // Create form data for submission
    const formData = new FormData();
    formData.append('nickname', nickname);
    
    console.log("Submitting form data");
    
    // Make AJAX request to submit the form
    fetch('script.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            console.error(`HTTP error! Status: ${response.status}`);
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        console.log("Response status:", response.status);
        return response.json();
    })
    .then(data => {
        console.log("Server response:", data);
        if (data.success) {
            // Sukces! Aplikacja została zapisana
            showMessage('Your application has been submitted successfully! You can now join our Discord.');
            
            // Odblokuj link Discord, ale tylko po pomyślnym zapisaniu na serwerze
            discordInvite.classList.remove('locked');
            discordInvite.classList.add('success-confirmed');
            
            // Ustawienie prawidłowego linku do Discorda
            const discordLink = discordInvite.querySelector('a');
            discordLink.href = 'https://discord.gg/NFdVCCUGcb';
            discordLink.setAttribute('target', '_blank');
            discordLink.onclick = null; // Usunięcie blokady onclick
            
            // Zmiana ikony na Discord
            discordInvite.querySelector('.contact-icons').innerHTML = '<i class="fab fa-discord"></i>';
            
            // Czyszczenie formularza
            document.getElementById('nicknameForm').reset();
        } else {
            // Error message
            showMessage(data.error || 'An error occurred. Please try again later.', true);
        }
    })
    .catch(error => {
        // Network or server error
        console.error('Error:', error);
        showMessage('Connection error. Please try again later.', true);
    })
    .finally(() => {
        // Re-enable submit button
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<span>Submit Application</span> <i class="fas fa-paper-plane"></i>';
    });
    
    return false;
}