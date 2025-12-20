"""
System tray manager for Sookth Mobile Pigmy Banking app.
Displays tray icon with Launch and Exit buttons.
"""

import pystray
from PIL import Image, ImageDraw
import threading
import webbrowser
import os
import sys
from updater import check_for_updates, perform_update, get_current_version

class TrayManager:
    def __init__(self, flask_app, flask_thread):
        """
        Initialize tray manager.
        
        Args:
            flask_app: The Flask app instance
            flask_thread: The thread running the Flask server
        """
        self.flask_app = flask_app
        self.flask_thread = flask_thread
        self.tray_icon = None
        self.update_available = False
        self.server_version = None
        
        # Check for updates on startup
        self.check_updates_background()
        
    def create_tray_icon(self):
        """Load custom ICO file."""
        try:
            base_path = sys._MEIPASS
        except Exception:
            base_path = os.path.abspath(".")
        return Image.open(os.path.join(base_path, "myicon.ico"))

    
    def launch_browser(self, icon, item):
        """Open the application in browser."""
        webbrowser.open("http://127.0.0.1:5000/login")
    
    def check_updates_background(self):
        """Check for updates in background thread."""
        def _check():
            try:
                update_available, server_version = check_for_updates()
                self.update_available = update_available
                self.server_version = server_version
                
                if update_available:
                    print(f"Update available: v{server_version}")
                    # Update tray icon title to show update notification
                    if self.tray_icon:
                        self.tray_icon.title = f"Sookth Mobile Pigmy Banking - Update Available (v{server_version})"
            except Exception as e:
                print(f"Update check failed: {e}")
        
        thread = threading.Thread(target=_check, daemon=True)
        thread.start()
    
    def check_for_updates_menu(self, icon, item):
        """Check for updates manually from menu."""
        def _check_and_notify():
            try:
                update_available, server_version = check_for_updates()
                self.update_available = update_available
                self.server_version = server_version
                
                if update_available:
                    self.tray_icon.title = f"Sookth Mobile Pigmy Banking - Update Available (v{server_version})"
                    # Show notification (Windows only)
                    try:
                        self.tray_icon.notify(
                            title="Update Available",
                            message=f"Version {server_version} is available. Click 'Update Now' to install."
                        )
                    except:
                        pass
                else:
                    try:
                        self.tray_icon.notify(
                            title="No Updates",
                            message=f"You are running the latest version ({get_current_version()})."
                        )
                    except:
                        pass
            except Exception as e:
                print(f"Update check failed: {e}")
        
        thread = threading.Thread(target=_check_and_notify, daemon=True)
        thread.start()
    
    def update_now(self, icon, item):
        """Download and install update."""
        if not self.update_available:
            return
        
        def _update():
            try:
                # Show notification
                try:
                    self.tray_icon.notify(
                        title="Updating...",
                        message="Downloading and installing update. The application will restart."
                    )
                except:
                    pass
                
                # Perform update (this will restart the app)
                perform_update()
            except Exception as e:
                print(f"Update failed: {e}")
                try:
                    self.tray_icon.notify(
                        title="Update Failed",
                        message=f"Failed to install update: {str(e)}"
                    )
                except:
                    pass
        
        thread = threading.Thread(target=_update, daemon=True)
        thread.start()
    
    def exit_app(self, icon, item):
        """Exit the application completely."""
        # Stop the tray icon
        self.tray_icon.stop()
        
        # Shutdown Flask gracefully
        try:
            func = self.flask_app.wsgi_app.environ.get('werkzeug.server.shutdown')
            if func is None:
                # For production servers, force exit
                os._exit(0)
            else:
                func()
        except:
            os._exit(0)
    
    def run(self):
        """Start the system tray icon."""
        def build_menu():
            """Build dynamic menu with update options."""
            items = [
                pystray.MenuItem(
                    "🚀 Launch Application",
                    self.launch_browser,
                    default=True
                ),
                pystray.MenuItem(
                    f"ℹ️ Version {get_current_version()}",
                    None,
                    enabled=False
                ),
                pystray.Menu.SEPARATOR,
                pystray.MenuItem(
                    "🔄 Check for Updates",
                    self.check_for_updates_menu
                )
            ]
            
            # Add update option if update is available
            if self.update_available:
                items.append(
                    pystray.MenuItem(
                        f"⬆️ Update to v{self.server_version}",
                        self.update_now
                    )
                )
            
            items.extend([
                pystray.Menu.SEPARATOR,
                pystray.MenuItem(
                    "❌ Exit",
                    self.exit_app
                )
            ])
            
            return pystray.Menu(*items)
        
        icon = pystray.Icon(
            name="SookthMobilePigmy",
            icon=self.create_tray_icon(),
            title="Sookth Mobile Pigmy Banking",
            menu=build_menu()
        )
        
        self.tray_icon = icon 
        icon.run()