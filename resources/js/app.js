import './bootstrap';
import { registerSW } from 'virtual:pwa-register';

// Register the service worker for PWA support
const updateSW = registerSW({
  onNeedRefresh() {
    if (confirm('New content available. Reload?')) {
      updateSW(true);
    }
  },
  onOfflineReady() {
    console.log('App ready to work offline');
  },
});

// PWA Install Prompt Logic
let deferredPrompt;

window.addEventListener('beforeinstallprompt', (e) => {
  // Prevent the mini-infobar from appearing on mobile
  e.preventDefault();
  // Stash the event so it can be triggered later.
  deferredPrompt = e;
  // Update UI notify user they can install the PWA
  window.dispatchEvent(new CustomEvent('pwa-installable'));
});

window.addEventListener('pwa-install-triggered', async () => {
  if (deferredPrompt) {
    // Show the install prompt
    deferredPrompt.prompt();
    // Wait for the user to respond to the prompt
    const { outcome } = await deferredPrompt.userChoice;
    // We've used the prompt, and can't use it again, throw it away
    deferredPrompt = null;
    // Hide the install UI
    window.dispatchEvent(new CustomEvent('pwa-installed'));
  }
});
