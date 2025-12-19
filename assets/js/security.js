/**
 * Security Script - Restrict Browser Features
 * Disables: Right-click, Inspect, View Source, Browser Navigation
 */

(function() {
    'use strict';

    // Disable right-click context menu
    document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
        showSecurityWarning();
        return false;
    });

    // Disable F12, Ctrl+Shift+I, Ctrl+Shift+J, Ctrl+U, Ctrl+S
    document.addEventListener('keydown', function(e) {
        // F12 - Developer Tools
        if (e.key === 'F12') {
            e.preventDefault();
            showSecurityWarning();
            return false;
        }
        
        // Ctrl+Shift+I - Inspect Element
        if (e.ctrlKey && e.shiftKey && e.key === 'I') {
            e.preventDefault();
            showSecurityWarning();
            return false;
        }
        
        // Ctrl+Shift+J - Console
        if (e.ctrlKey && e.shiftKey && e.key === 'J') {
            e.preventDefault();
            showSecurityWarning();
            return false;
        }
        
        // Ctrl+Shift+C - Inspect Element
        if (e.ctrlKey && e.shiftKey && e.key === 'C') {
            e.preventDefault();
            showSecurityWarning();
            return false;
        }
        
        // Ctrl+U - View Source
        if (e.ctrlKey && e.key === 'u') {
            e.preventDefault();
            showSecurityWarning();
            return false;
        }
        
        // Ctrl+S - Save Page
        if (e.ctrlKey && e.key === 's') {
            e.preventDefault();
            showSecurityWarning();
            return false;
        }
        
        // Ctrl+P - Print (optional, uncomment to disable)
        // if (e.ctrlKey && e.key === 'p') {
        //     e.preventDefault();
        //     return false;
        // }
    });

    // Disable text selection (optional, can be restrictive)
    // document.addEventListener('selectstart', function(e) {
    //     e.preventDefault();
    //     return false;
    // });

    // Disable copy (optional)
    // document.addEventListener('copy', function(e) {
    //     e.preventDefault();
    //     return false;
    // });

    // Detect DevTools (partial detection - not foolproof)
    let devtools = { open: false };
    let threshold = 160;

    const detectDevTools = () => {
        if (window.outerWidth - window.innerWidth > threshold || 
            window.outerHeight - window.innerHeight > threshold) {
            if (!devtools.open) {
                devtools.open = true;
                document.body.innerHTML = '<div style="display:flex;align-items:center;justify-content:center;height:100vh;font-family:Arial;"><div style="text-align:center;"><h1>⚠️ Security Alert</h1><p>Developer tools detected. Please close them to continue.</p></div></div>';
            }
        } else {
            devtools.open = false;
        }
    };

    // Check periodically (can be resource intensive)
    // setInterval(detectDevTools, 500);

    // Prevent browser back/forward buttons from affecting the page state
    window.history.pushState(null, null, window.location.href);
    window.addEventListener('popstate', function(e) {
        window.history.pushState(null, null, window.location.href);
        // Optional: Show warning
        // showSecurityWarning('Please use the application\'s navigation buttons.');
    });

    // Clear browser cache on page unload (session only)
    window.addEventListener('beforeunload', function() {
        if (typeof Storage !== 'undefined') {
            // sessionStorage.clear(); // Uncomment if needed
        }
    });

    // Show security warning message
    let warningTimeout;
    function showSecurityWarning(message) {
        // Clear existing timeout
        if (warningTimeout) clearTimeout(warningTimeout);
        
        // Create or update warning element
        let warning = document.getElementById('security-warning');
        if (!warning) {
            warning = document.createElement('div');
            warning.id = 'security-warning';
            warning.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: #dc3545;
                color: white;
                padding: 15px 20px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.3);
                z-index: 999999;
                font-family: Arial, sans-serif;
                font-size: 14px;
                max-width: 300px;
                animation: slideIn 0.3s ease-out;
            `;
            document.body.appendChild(warning);
            
            // Add animation
            const style = document.createElement('style');
            style.textContent = `
                @keyframes slideIn {
                    from { transform: translateX(400px); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
                @keyframes slideOut {
                    from { transform: translateX(0); opacity: 1; }
                    to { transform: translateX(400px); opacity: 0; }
                }
            `;
            document.head.appendChild(style);
        }
        
        warning.innerHTML = `
            <strong>⚠️ Security Alert</strong><br>
            ${message || 'This action is restricted for security reasons.'}
        `;
        warning.style.display = 'block';
        
        // Auto-hide after 3 seconds
        warningTimeout = setTimeout(function() {
            warning.style.animation = 'slideOut 0.3s ease-out';
            setTimeout(function() {
                warning.style.display = 'none';
                warning.style.animation = 'slideIn 0.3s ease-out';
            }, 300);
        }, 3000);
    }

    // Disable drag and drop
    document.addEventListener('dragstart', function(e) {
        e.preventDefault();
        return false;
    });

    // Console message for developers
    console.log('%c⚠️ STOP!', 'color: red; font-size: 40px; font-weight: bold;');
    console.log('%cThis is a browser feature intended for developers. If someone told you to copy-paste something here, it is a scam and will give them access to your account.', 'font-size: 16px;');
    console.log('%cSookth Mobile Pigmy - Unauthorized access is prohibited.', 'font-size: 14px; color: #666;');

})();
