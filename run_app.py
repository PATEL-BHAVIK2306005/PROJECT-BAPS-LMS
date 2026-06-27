import os
import sys

# Reconfigure stdout/stderr to UTF-8 to prevent UnicodeEncodeError on Windows
if sys.stdout is not None:
    try:
        sys.stdout.reconfigure(encoding='utf-8')
    except Exception:
        pass
if sys.stderr is not None:
    try:
        sys.stderr.reconfigure(encoding='utf-8')
    except Exception:
        pass

import time
import socket
import threading
import subprocess
import webbrowser
import argparse
import tkinter as tk
from tkinter import messagebox

try:
    import webview
    HAS_WEBVIEW = True
except ImportError:
    HAS_WEBVIEW = False

try:
    import customtkinter as ctk
    HAS_CUSTOMTKINTER = True
except ImportError:
    HAS_CUSTOMTKINTER = False

# Configurable settings
XAMPP_DIR = r"C:\xampppp" if os.path.exists(r"C:\xampppp") else r"C:\xampp"

# Global references
log_widget = None
apache_status_lbl = None
mysql_status_lbl = None
artisan_status_lbl = None
launch_target = "desktop" # 'desktop' or 'browser'

status_data = {
    "apache": False,
    "mysql": False,
    "artisan": False
}

def get_path(subpath):
    return os.path.join(XAMPP_DIR, subpath)

def get_local_ip():
    try:
        s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
        s.settimeout(0.1)
        s.connect(("8.8.8.8", 80))
        ip = s.getsockname()[0]
        s.close()
        return ip
    except Exception:
        return "127.0.0.1"

def launch_webview(url):
    if not HAS_WEBVIEW:
        print("pywebview is not installed. Falling back to default web browser.")
        webbrowser.open(url)
        return
    try:
        webview.create_window(
            title="Deela LMS - Standalone Desktop App",
            url=url,
            width=1280,
            height=800,
            resizable=True,
            min_size=(1024, 768)
        )
        webview.start()
    except Exception as e:
        print(f"Error starting webview window: {e}")
        webbrowser.open(url)

def check_process(name):
    """Check if process is running by name on Windows."""
    try:
        output = subprocess.check_output(f'tasklist /NH /FI "IMAGENAME eq {name}"', shell=True).decode('utf-8', errors='ignore')
        return name.lower() in output.lower()
    except Exception:
        return False

def check_port(port):
    """Check if port is listening on localhost."""
    try:
        with socket.socket(socket.AF_INET, socket.SOCK_STREAM) as s:
            s.settimeout(0.3)
            return s.connect_ex(('127.0.0.1', port)) == 0
    except Exception:
        return False

def bg_status_poller():
    """Background status polling loop."""
    while True:
        status_data["apache"] = check_process("httpd.exe") or check_port(80)
        status_data["mysql"] = check_process("mysqld.exe") or check_port(3306)
        status_data["artisan"] = check_port(8000)
        time.sleep(1.5)

def log_message(msg):
    """Log messaging helper."""
    timestamp = time.strftime("%H:%M:%S")
    full_msg = f"[{timestamp}] {msg}"
    print(full_msg)
    
    # Safe GUI updating
    if log_widget:
        try:
            log_widget.configure(state='normal')
            log_widget.insert('end', full_msg + "\n")
            log_widget.see('end')
            log_widget.configure(state='disabled')
        except Exception:
            pass

def start_apache():
    apache_exe = get_path(r"apache\bin\httpd.exe")
    if os.path.exists(apache_exe):
        if not (check_process("httpd.exe") or check_port(80)):
            try:
                subprocess.Popen([apache_exe], cwd=get_path(r"apache\bin"), creationflags=subprocess.CREATE_NO_WINDOW)
                log_message("Apache Web Server starting...")
            except Exception as e:
                log_message(f"Failed to start Apache: {e}")
        else:
            log_message("Apache is already running.")
    else:
        log_message("Apache executable not found in XAMPP directory.")

def start_mysql():
    mysql_exe = get_path(r"mysql\bin\mysqld.exe")
    if os.path.exists(mysql_exe):
        if not (check_process("mysqld.exe") or check_port(3306)):
            try:
                subprocess.Popen([mysql_exe, "--defaults-file=my.ini", "--standalone"], cwd=get_path(r"mysql\bin"), creationflags=subprocess.CREATE_NO_WINDOW)
                log_message("MySQL Database Server starting...")
            except Exception as e:
                log_message(f"Failed to start MySQL: {e}")
        else:
            log_message("MySQL is already running.")
    else:
        log_message("MySQL executable not found in XAMPP directory.")

def start_artisan():
    if not check_port(8000):
        try:
            proj_dir = os.path.dirname(os.path.abspath(__file__))
            subprocess.Popen(["php", "artisan", "serve"], cwd=proj_dir, creationflags=subprocess.CREATE_NO_WINDOW)
            log_message("Laravel Artisan Server starting on http://127.0.0.1:8000 ...")
        except Exception as e:
            log_message(f"Failed to start Artisan Serve: {e}")
    else:
        log_message("Artisan Server is already running on port 8000.")

def kill_port_process(port):
    try:
        output = subprocess.check_output("netstat -ano", shell=True).decode('utf-8', errors='ignore')
        for line in output.splitlines():
            if f"127.0.0.1:{port}" in line or f"0.0.0.0:{port}" in line or f"[::]:{port}" in line:
                parts = line.strip().split()
                if len(parts) >= 5:
                    pid = parts[-1]
                    subprocess.run(f"taskkill /F /PID {pid}", shell=True, stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL)
                    log_message(f"Stopped process on port {port} (PID: {pid}).")
    except Exception as e:
        log_message(f"Error killing process on port {port}: {e}")

def stop_all_services():
    log_message("Stopping all local services...")
    # Kill Apache
    if check_process("httpd.exe"):
        subprocess.run("taskkill /F /IM httpd.exe", shell=True, stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL)
        log_message("Stopped Apache Web Server.")
    # Kill MySQL
    if check_process("mysqld.exe"):
        subprocess.run("taskkill /F /IM mysqld.exe", shell=True, stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL)
        log_message("Stopped MySQL Database Server.")
    # Kill Artisan serve on 8000
    if check_port(8000):
        kill_port_process(8000)
        log_message("Stopped Laravel Artisan Server.")
    log_message("All services stopped successfully.")

def launch_application(mode):
    """
    mode: 'xampp', 'artisan', or 'online'
    """
    if mode == "xampp":
        log_message("🚀 Starting Services: Apache Web Server + MySQL Database")
        start_mysql()
        start_apache()
        url = "http://localhost/deela/public/"
    elif mode == "online":
        log_message("🚀 Starting Services: Laravel Dev Server (Artisan) only [Online Database Mode]")
        if check_process("httpd.exe"):
            subprocess.run("taskkill /F /IM httpd.exe", shell=True, stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL)
            log_message("Stopped Apache to prevent conflicts.")
        start_artisan()
        url = "http://127.0.0.1:8000"
    else:
        log_message("🚀 Starting Services: Laravel Dev Server (Artisan) + MySQL Database")
        start_mysql()
        if check_process("httpd.exe"):
            subprocess.run("taskkill /F /IM httpd.exe", shell=True, stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL)
            log_message("Stopped Apache to prevent conflicts.")
        start_artisan()
        url = "http://127.0.0.1:8000"
    
    # Give the services a moment to spin up and bind to their ports
    time.sleep(2.0)
    
    if launch_target == "desktop":
        log_message(f"🖥️ Launching Desktop App window: {url}")
        python_exe = sys.executable
        script_path = os.path.abspath(__file__)
        try:
            # Launch pywebview frame inside a subprocess to keep Tkinter responsive
            proc = subprocess.Popen([python_exe, script_path, "--webview", url])
            
            if 'root' in globals() and root:
                def monitor():
                    try:
                        root.iconify() # Minimize the control center window
                    except Exception:
                        pass
                    
                    proc.wait() # Wait until desktop window is closed
                    
                    try:
                        root.deiconify() # Restore the control center window
                    except Exception:
                        pass
                    
                    ans = messagebox.askyesno(
                        "Deela LMS Desktop",
                        "The Standalone Desktop window has been closed.\n\nDo you want to stop Apache, MySQL, and Artisan background servers?"
                    )
                    if ans:
                        stop_all_services()
                
                threading.Thread(target=monitor, daemon=True).start()
            else:
                proc.wait()
                stop_all_services()
        except Exception as e:
            log_message(f"Failed to start desktop webview: {e}. Falling back to default browser.")
            webbrowser.open(url)
    else:
        log_message(f"🌐 Launching Web App in default Web Browser: {url}")
        webbrowser.open(url)

def create_desktop_shortcut():
    try:
        desktop = os.path.join(os.environ['USERPROFILE'], 'Desktop')
        shortcut_path = os.path.join(desktop, 'Deela LMS.lnk')
        
        project_dir = os.path.dirname(os.path.abspath(__file__))
        batch_path = os.path.join(project_dir, 'run_gui.bat')
        icon_path = os.path.join(project_dir, 'public', 'favicon.ico')
        
        powershell_cmd = (
            f'$WshShell = New-Object -ComObject WScript.Shell; '
            f'$Shortcut = $WshShell.CreateShortcut("{shortcut_path}"); '
            f'$Shortcut.TargetPath = "{batch_path}"; '
            f'$Shortcut.WorkingDirectory = "{project_dir}"; '
            f'$Shortcut.IconLocation = "{icon_path}"; '
            f'$Shortcut.Save()'
        )
        
        subprocess.run(['powershell', '-Command', powershell_cmd], check=True, creationflags=subprocess.CREATE_NO_WINDOW)
        log_message(f"Desktop shortcut created successfully at: {shortcut_path}")
        return True, shortcut_path
    except Exception as e:
        log_message(f"Failed to create desktop shortcut: {e}")
        return False, str(e)

# Modern Tkinter Hover Button Class
class HoverButton(tk.Button):
    def __init__(self, master, active_bg, active_fg, **kw):
        super().__init__(master, **kw)
        self.default_bg = self['bg']
        self.default_fg = self['fg']
        self.active_bg = active_bg
        self.active_fg = active_fg
        self.config(relief="flat", activebackground=active_bg, activeforeground=active_fg, cursor="hand2")
        self.bind("<Enter>", self.on_enter)
        self.bind("<Leave>", self.on_leave)

    def on_enter(self, e):
        self.config(bg=self.active_bg, fg=self.active_fg)

    def on_leave(self, e):
        self.config(bg=self.default_bg, fg=self.default_fg)

def run_in_thread(target_func):
    threading.Thread(target=target_func, daemon=True).start()

def create_desktop_shortcut_gui():
    success, details = create_desktop_shortcut()
    if success:
        messagebox.showinfo("Success", f"Desktop App Icon created successfully!\n\nLocation:\n{details}")
    else:
        messagebox.showerror("Error", f"Failed to create shortcut:\n{details}")

def update_gui_status():
    if apache_status_lbl and mysql_status_lbl and artisan_status_lbl:
        try:
            is_ctk = HAS_CUSTOMTKINTER and isinstance(apache_status_lbl, ctk.CTkLabel)
            
            if status_data["apache"]:
                if is_ctk:
                    apache_status_lbl.configure(text="🟢 RUNNING", text_color="#a6e3a1")
                else:
                    apache_status_lbl.config(text="🟢 RUNNING", fg="#a6e3a1")
            else:
                if is_ctk:
                    apache_status_lbl.configure(text="🔴 STOPPED", text_color="#f38ba8")
                else:
                    apache_status_lbl.config(text="🔴 STOPPED", fg="#f38ba8")
                
            if status_data["mysql"]:
                if is_ctk:
                    mysql_status_lbl.configure(text="🟢 RUNNING", text_color="#a6e3a1")
                else:
                    mysql_status_lbl.config(text="🟢 RUNNING", fg="#a6e3a1")
            else:
                if is_ctk:
                    mysql_status_lbl.configure(text="🔴 STOPPED", text_color="#f38ba8")
                else:
                    mysql_status_lbl.config(text="🔴 STOPPED", fg="#f38ba8")
                
            if status_data["artisan"]:
                if is_ctk:
                    artisan_status_lbl.configure(text="🟢 RUNNING (Port 8000)", text_color="#a6e3a1")
                else:
                    artisan_status_lbl.config(text="🟢 RUNNING (Port 8000)", fg="#a6e3a1")
            else:
                if is_ctk:
                    artisan_status_lbl.configure(text="🔴 STOPPED", text_color="#f38ba8")
                else:
                    artisan_status_lbl.config(text="🔴 STOPPED", fg="#f38ba8")
        except Exception:
            pass
        
    # Schedule next run in 1s
    if 'root' in globals() and root:
        try:
            root.after(1000, update_gui_status)
        except Exception:
            pass

def build_custom_gui():
    global log_widget, apache_status_lbl, mysql_status_lbl, artisan_status_lbl, root, launch_target
    
    ctk.set_appearance_mode("dark")
    ctk.set_default_color_theme("blue")
    
    root = ctk.CTk()
    root.title("Deela LMS Control Center")
    root.geometry("640x750")
    root.resizable(False, False)
    
    try:
        project_dir = os.path.dirname(os.path.abspath(__file__))
        icon_path = os.path.join(project_dir, 'public', 'favicon.ico')
        if os.path.exists(icon_path):
            root.iconbitmap(icon_path)
    except Exception:
        pass
        
    # Header Frame
    header_frame = ctk.CTkFrame(root, corner_radius=12, fg_color=("#1e3a8a", "#0f172a"))
    header_frame.pack(fill="x", padx=20, pady=(15, 10))
    
    header_title = ctk.CTkLabel(header_frame, text="DEELA LMS CONTROL CENTER", font=("Segoe UI", 18, "bold"), text_color="#f9e2af")
    header_title.pack(pady=(12, 2))
    
    header_subtitle = ctk.CTkLabel(header_frame, text="Academic Web Portal & Local Server Launcher", font=("Segoe UI", 10), text_color="#a6adc8")
    header_subtitle.pack(pady=(0, 2))
    
    workspace_lbl = ctk.CTkLabel(header_frame, text=f"Workspace: {os.path.dirname(os.path.abspath(__file__))}", font=("Consolas", 9), text_color="#a6adc8")
    workspace_lbl.pack(pady=(0, 12))

    # Target Type Selector Frame
    target_frame = ctk.CTkFrame(root, corner_radius=10)
    target_frame.pack(fill="x", padx=20, pady=5)
    
    target_title = ctk.CTkLabel(target_frame, text="Application Launcher Settings", font=("Segoe UI", 11, "bold"), text_color="#f9e2af")
    target_title.pack(anchor="w", padx=15, pady=(8, 2))
    
    app_type_var = ctk.StringVar(value="desktop" if launch_target == "desktop" else "browser")
    
    def update_target_selection():
        global launch_target
        if launch_target == "browser":
            local_ip = get_local_ip()
            lan_info_lbl.configure(
                text=f"📶 LAN Web Server Mode Active!\nOthers on your Wi-Fi can connect via:\n• http://{local_ip}:8000 (Artisan)\n• http://{local_ip}/deela/public/ (XAMPP)"
            )
            lan_info_lbl.pack(fill="x", padx=15, pady=(0, 10))
        else:
            lan_info_lbl.pack_forget()

    def on_segmented_change(value):
        global launch_target
        launch_target = "desktop" if "Desktop" in value else "browser"
        app_type_var.set(launch_target)
        update_target_selection()

    seg_button = ctk.CTkSegmentedButton(
        target_frame,
        values=["🖥️ Standalone Desktop App", "🌐 Web Browser Mode"],
        command=on_segmented_change,
        font=("Segoe UI", 10, "bold")
    )
    seg_button.pack(fill="x", padx=15, pady=(5, 10))
    seg_button.set("🖥️ Standalone Desktop App" if launch_target == "desktop" else "🌐 Web Browser Mode")

    local_ip = get_local_ip()
    lan_info_lbl = ctk.CTkLabel(
        target_frame,
        text=f"📶 LAN Web Server Mode Active!\nOthers on your Wi-Fi can connect via:\n• http://{local_ip}:8000 (Artisan)\n• http://{local_ip}/deela/public/ (XAMPP)",
        text_color="#a6e3a1",
        fg_color=("#ffffff", "#1e1e2e"),
        corner_radius=6,
        font=("Consolas", 10),
        justify="left",
        anchor="w",
        padx=10,
        pady=5
    )
    
    # Initialize target view on load
    update_target_selection()

    # Status Panel Frame
    status_frame = ctk.CTkFrame(root, corner_radius=10)
    status_frame.pack(fill="x", padx=20, pady=5)
    
    # Status Header
    status_header = ctk.CTkFrame(status_frame, fg_color="transparent")
    status_header.pack(fill="x", padx=15, pady=(8, 2))
    
    status_title = ctk.CTkLabel(status_header, text="Service Status Tracker", font=("Segoe UI", 11, "bold"), text_color="#f9e2af")
    status_title.pack(side="left")
    
    def force_refresh_services():
        log_message("Force polling service statuses...")
        status_data["apache"] = check_process("httpd.exe") or check_port(80)
        status_data["mysql"] = check_process("mysqld.exe") or check_port(3306)
        status_data["artisan"] = check_port(8000)
        update_gui_status()
        log_message(f"Status refreshed. Apache: {'ON' if status_data['apache'] else 'OFF'}, MySQL: {'ON' if status_data['mysql'] else 'OFF'}, Laravel: {'ON' if status_data['artisan'] else 'OFF'}")

    btn_refresh = ctk.CTkButton(
        status_header,
        text="🔄 Refresh Status",
        width=110,
        height=24,
        font=("Segoe UI", 9, "bold"),
        fg_color="#3b82f6",
        hover_color="#2563eb",
        command=force_refresh_services
    )
    btn_refresh.pack(side="right")
    
    # Grid for services status
    status_grid = ctk.CTkFrame(status_frame, fg_color="transparent")
    status_grid.pack(fill="x", padx=15, pady=(2, 10))
    
    # Apache Status Row
    lbl_ap = ctk.CTkLabel(status_grid, text="Apache Web Server (Port 80):", font=("Segoe UI", 10), text_color="#cdd6f4")
    lbl_ap.grid(row=0, column=0, sticky="w", pady=3)
    apache_status_lbl = ctk.CTkLabel(status_grid, text="● CHECKING...", font=("Segoe UI", 10, "bold"), text_color="#f9e2af")
    apache_status_lbl.grid(row=0, column=1, sticky="w", padx=15, pady=3)
    
    # MySQL Status Row
    lbl_my = ctk.CTkLabel(status_grid, text="MySQL Database (Port 3306):", font=("Segoe UI", 10), text_color="#cdd6f4")
    lbl_my.grid(row=1, column=0, sticky="w", pady=3)
    mysql_status_lbl = ctk.CTkLabel(status_grid, text="● CHECKING...", font=("Segoe UI", 10, "bold"), text_color="#f9e2af")
    mysql_status_lbl.grid(row=1, column=1, sticky="w", padx=15, pady=3)
    
    # Artisan Serve Status Row
    lbl_art = ctk.CTkLabel(status_grid, text="Laravel Dev Server (Port 8000):", font=("Segoe UI", 10), text_color="#cdd6f4")
    lbl_art.grid(row=2, column=0, sticky="w", pady=3)
    artisan_status_lbl = ctk.CTkLabel(status_grid, text="● CHECKING...", font=("Segoe UI", 10, "bold"), text_color="#f9e2af")
    artisan_status_lbl.grid(row=2, column=1, sticky="w", padx=15, pady=3)
    
    status_grid.grid_columnconfigure(0, weight=1)
    status_grid.grid_columnconfigure(1, weight=1)

    # Actions Frame
    actions_frame = ctk.CTkFrame(root, fg_color="transparent")
    actions_frame.pack(fill="x", padx=20, pady=5)
    
    btn_xampp = ctk.CTkButton(
        actions_frame,
        text="🚀 Launch Mode 1: With XAMPP (Apache + MySQL)",
        font=("Segoe UI", 11, "bold"),
        fg_color="#3b82f6",
        hover_color="#2563eb",
        text_color="#ffffff",
        height=38,
        command=lambda: run_in_thread(lambda: launch_application("xampp"))
    )
    btn_xampp.grid(row=0, column=0, columnspan=2, sticky="we", pady=4)
    
    btn_artisan = ctk.CTkButton(
        actions_frame,
        text="⚡ Launch Mode 2: Without Apache (Artisan + MySQL)",
        font=("Segoe UI", 11, "bold"),
        fg_color="#10b981",
        hover_color="#059669",
        text_color="#ffffff",
        height=38,
        command=lambda: run_in_thread(lambda: launch_application("artisan"))
    )
    btn_artisan.grid(row=1, column=0, columnspan=2, sticky="we", pady=4)
    
    btn_online = ctk.CTkButton(
        actions_frame,
        text="☁️ Launch Mode 3: Online Cloud Mode (Artisan - No XAMPP)",
        font=("Segoe UI", 11, "bold"),
        fg_color="#0ea5e9",
        hover_color="#0284c7",
        text_color="#ffffff",
        height=38,
        command=lambda: run_in_thread(lambda: launch_application("online"))
    )
    btn_online.grid(row=2, column=0, columnspan=2, sticky="we", pady=4)
    
    btn_stop = ctk.CTkButton(
        actions_frame,
        text="🛑 Stop All Services",
        font=("Segoe UI", 11, "bold"),
        fg_color="#ef4444",
        hover_color="#dc2626",
        text_color="#ffffff",
        height=38,
        command=lambda: run_in_thread(stop_all_services)
    )
    btn_stop.grid(row=3, column=0, sticky="we", pady=4, padx=(0, 4))
    
    btn_phpmyadmin = ctk.CTkButton(
        actions_frame,
        text="📁 Open phpMyAdmin",
        font=("Segoe UI", 11, "bold"),
        fg_color="#f59e0b",
        hover_color="#d97706",
        text_color="#ffffff",
        height=38,
        command=lambda: webbrowser.open("http://localhost/phpmyadmin")
    )
    btn_phpmyadmin.grid(row=3, column=1, sticky="we", pady=4, padx=(4, 0))
    
    actions_frame.grid_columnconfigure(0, weight=1)
    actions_frame.grid_columnconfigure(1, weight=1)
    
    # Utility Buttons Frame (Shortcut + Theme)
    util_frame = ctk.CTkFrame(root, fg_color="transparent")
    util_frame.pack(fill="x", padx=20, pady=5)
    
    btn_shortcut = ctk.CTkButton(
        util_frame,
        text="✨ Create Desktop App Icon",
        font=("Segoe UI", 11, "bold"),
        fg_color="#8b5cf6",
        hover_color="#7c3aed",
        text_color="#ffffff",
        height=32,
        command=create_desktop_shortcut_gui
    )
    btn_shortcut.pack(side="left", fill="x", expand=True, padx=(0, 4))
    
    def toggle_theme():
        current_mode = ctk.get_appearance_mode()
        new_mode = "Light" if current_mode == "Dark" else "Dark"
        ctk.set_appearance_mode(new_mode)
        log_message(f"Appearance theme switched to: {new_mode}")
        
    btn_theme = ctk.CTkButton(
        util_frame,
        text="🌓 Toggle Dark/Light Mode",
        font=("Segoe UI", 11, "bold"),
        fg_color=("#4b5563", "#374151"),
        hover_color=("#374151", "#1f2937"),
        text_color="#ffffff",
        height=32,
        command=toggle_theme
    )
    btn_theme.pack(side="right", fill="x", expand=True, padx=(4, 0))

    # Logs Frame
    logs_frame = ctk.CTkFrame(root, corner_radius=10)
    logs_frame.pack(fill="both", expand=True, padx=20, pady=(5, 15))
    
    logs_title = ctk.CTkLabel(logs_frame, text="Activity Logs Terminal", font=("Segoe UI", 10, "bold"), text_color="#f9e2af")
    logs_title.pack(anchor="w", padx=15, pady=(8, 2))
    
    log_widget = ctk.CTkTextbox(
        logs_frame,
        font=("Consolas", 10),
        fg_color="#0f172a",
        text_color="#38bdf8",
        corner_radius=8,
        border_width=1,
        border_color="#334155"
    )
    log_widget.pack(fill="both", expand=True, padx=12, pady=(0, 12))
    log_widget.configure(state="disabled")
    
    # Start poller thread
    poller_thread = threading.Thread(target=bg_status_poller, daemon=True)
    poller_thread.start()
    
    # Run status updates
    update_gui_status()
    
    log_message("Deela LMS Control Center Custom GUI started successfully.")
    
    def on_closing():
        root.destroy()
        sys.exit(0)
        
    root.protocol("WM_DELETE_WINDOW", on_closing)
    root.mainloop()

def build_standard_gui():
    global log_widget, apache_status_lbl, mysql_status_lbl, artisan_status_lbl, root, launch_target
    
    root = tk.Tk()
    root.title("Deela LMS Control Center")
    root.geometry("600x670")
    root.configure(bg="#181825")
    root.resizable(False, False)
    
    try:
        project_dir = os.path.dirname(os.path.abspath(__file__))
        icon_path = os.path.join(project_dir, 'public', 'favicon.ico')
        if os.path.exists(icon_path):
            root.iconbitmap(icon_path)
    except Exception:
        pass

    # Header Frame
    header_frame = tk.Frame(root, bg="#11111b", height=80)
    header_frame.pack(fill="x")
    header_frame.pack_propagate(False)
    
    header_title = tk.Label(header_frame, text="DEELA LMS CONTROL CENTER", fg="#f9e2af", bg="#11111b", font=("Segoe UI", 14, "bold"))
    header_title.pack(pady=(12, 2))
    header_subtitle = tk.Label(header_frame, text=f"Workspace: {os.path.dirname(os.path.abspath(__file__))}", fg="#a6adc8", bg="#11111b", font=("Segoe UI", 9))
    header_subtitle.pack()

    # Target Type Selector Frame (New)
    target_frame = tk.LabelFrame(root, text=" Application Launcher Type ", fg="#f9e2af", bg="#181825", font=("Segoe UI", 10, "bold"), bd=1, relief="solid", padx=15, pady=8)
    target_frame.pack(fill="x", padx=20, pady=(15, 5))
    
    app_type_var = tk.StringVar(value=launch_target)
    
    def update_target_selection():
        global launch_target
        launch_target = app_type_var.get()
        if launch_target == "browser":
            local_ip = get_local_ip()
            lan_info_lbl.config(
                text=f"📶 LAN Web Server Mode Active!\nOthers on your Wi-Fi can connect via:\n• http://{local_ip}:8000 (Artisan)\n• http://{local_ip}/deela/public/ (XAMPP)"
            )
            lan_info_lbl.pack(fill="x", pady=(5, 0))
        else:
            lan_info_lbl.pack_forget()

    rb_desktop = tk.Radiobutton(
        target_frame, 
        text="🖥️ Standalone Desktop App (Distraction-Free Native Frame)", 
        variable=app_type_var, 
        value="desktop", 
        bg="#181825", 
        fg="#cdd6f4", 
        selectcolor="#11111b", 
        font=("Segoe UI", 9, "bold" if launch_target == "desktop" else "normal"), 
        activebackground="#181825", 
        activeforeground="#cdd6f4", 
        command=update_target_selection
    )
    rb_desktop.pack(anchor="w", pady=2)
    
    rb_browser = tk.Radiobutton(
        target_frame, 
        text="🌐 Standard Web Browser Mode (Opens Default Browser + Share on LAN)", 
        variable=app_type_var, 
        value="browser", 
        bg="#181825", 
        fg="#cdd6f4", 
        selectcolor="#11111b", 
        font=("Segoe UI", 9, "bold" if launch_target == "browser" else "normal"), 
        activebackground="#181825", 
        activeforeground="#cdd6f4", 
        command=update_target_selection
    )
    rb_browser.pack(anchor="w", pady=2)

    local_ip = get_local_ip()
    lan_info_lbl = tk.Label(
        target_frame, 
        text=f"📶 LAN Web Server Mode Active!\nOthers on your Wi-Fi can connect via:\n• http://{local_ip}:8000 (Artisan)\n• http://{local_ip}/deela/public/ (XAMPP)", 
        fg="#a6e3a1", 
        bg="#11111b", 
        font=("Consolas", 9), 
        justify="left", 
        padx=10, 
        pady=5
    )
    
    # Initialize target view on load
    update_target_selection()

    # Status Panel Frame
    status_frame = tk.LabelFrame(root, text=" Service Status ", fg="#f9e2af", bg="#181825", font=("Segoe UI", 10, "bold"), bd=1, relief="solid", padx=15, pady=8)
    status_frame.pack(fill="x", padx=20, pady=5)
    
    # Apache Status Row
    tk.Label(status_frame, text="Apache Web Server (Port 80):", fg="#cdd6f4", bg="#181825", font=("Segoe UI", 10)).grid(row=0, column=0, sticky="w", pady=3)
    apache_status_lbl = tk.Label(status_frame, text="● CHECKING...", fg="#f9e2af", bg="#181825", font=("Segoe UI", 10, "bold"))
    apache_status_lbl.grid(row=0, column=1, sticky="w", padx=15)
    
    # MySQL Status Row
    tk.Label(status_frame, text="MySQL Database (Port 3306):", fg="#cdd6f4", bg="#181825", font=("Segoe UI", 10)).grid(row=1, column=0, sticky="w", pady=3)
    mysql_status_lbl = tk.Label(status_frame, text="● CHECKING...", fg="#f9e2af", bg="#181825", font=("Segoe UI", 10, "bold"))
    mysql_status_lbl.grid(row=1, column=1, sticky="w", padx=15)
    
    # Artisan Serve Status Row
    tk.Label(status_frame, text="Laravel Dev Server (Port 8000):", fg="#cdd6f4", bg="#181825", font=("Segoe UI", 10)).grid(row=2, column=0, sticky="w", pady=3)
    artisan_status_lbl = tk.Label(status_frame, text="● CHECKING...", fg="#f9e2af", bg="#181825", font=("Segoe UI", 10, "bold"))
    artisan_status_lbl.grid(row=2, column=1, sticky="w", padx=15)

    def force_refresh_services():
        log_message("Force polling service statuses...")
        status_data["apache"] = check_process("httpd.exe") or check_port(80)
        status_data["mysql"] = check_process("mysqld.exe") or check_port(3306)
        status_data["artisan"] = check_port(8000)
        update_gui_status()
        log_message(f"Status refreshed. Apache: {'ON' if status_data['apache'] else 'OFF'}, MySQL: {'ON' if status_data['mysql'] else 'OFF'}, Laravel: {'ON' if status_data['artisan'] else 'OFF'}")

    btn_refresh = HoverButton(status_frame, text="🔄 Refresh", bg="#89b4fa", fg="#11111b", font=("Segoe UI", 9, "bold"), bd=0, padx=8, pady=4, active_bg="#b4befe", active_fg="#11111b", command=force_refresh_services)
    btn_refresh.grid(row=0, column=2, rowspan=3, padx=20, pady=5, sticky="ns")

    # Actions Frame
    actions_frame = tk.Frame(root, bg="#181825")
    actions_frame.pack(fill="x", padx=20, pady=5)
    
    # Action Buttons
    btn_xampp = HoverButton(actions_frame, text="🚀 Launch Mode 1: With XAMPP (Apache + MySQL)", bg="#89b4fa", fg="#11111b", font=("Segoe UI", 10, "bold"), bd=0, padx=10, pady=8, active_bg="#b4befe", active_fg="#11111b", command=lambda: run_in_thread(lambda: launch_application("xampp")))
    btn_xampp.grid(row=0, column=0, columnspan=2, sticky="we", pady=5, padx=5)
    
    btn_artisan = HoverButton(actions_frame, text="⚡ Launch Mode 2: Without Apache (Artisan + MySQL)", bg="#a6e3a1", fg="#11111b", font=("Segoe UI", 10, "bold"), bd=0, padx=10, pady=8, active_bg="#94e2d5", active_fg="#11111b", command=lambda: run_in_thread(lambda: launch_application("artisan")))
    btn_artisan.grid(row=1, column=0, columnspan=2, sticky="we", pady=5, padx=5)
    
    btn_online = HoverButton(actions_frame, text="☁️ Launch Mode 3: Online Cloud Mode (Artisan - No XAMPP)", bg="#0ea5e9", fg="#ffffff", font=("Segoe UI", 10, "bold"), bd=0, padx=10, pady=8, active_bg="#0284c7", active_fg="#ffffff", command=lambda: run_in_thread(lambda: launch_application("online")))
    btn_online.grid(row=2, column=0, columnspan=2, sticky="we", pady=5, padx=5)
    
    btn_stop = HoverButton(actions_frame, text="🛑 Stop All Services", bg="#f38ba8", fg="#11111b", font=("Segoe UI", 10, "bold"), bd=0, padx=10, pady=8, active_bg="#eba0ac", active_fg="#11111b", command=lambda: run_in_thread(stop_all_services))
    btn_stop.grid(row=3, column=0, sticky="we", pady=5, padx=5)
    
    btn_phpmyadmin = HoverButton(actions_frame, text="📁 Open phpMyAdmin", bg="#f9e2af", fg="#11111b", font=("Segoe UI", 10, "bold"), bd=0, padx=10, pady=8, active_bg="#f5e0dc", active_fg="#11111b", command=lambda: webbrowser.open("http://localhost/phpmyadmin"))
    btn_phpmyadmin.grid(row=3, column=1, sticky="we", pady=5, padx=5)
    
    actions_frame.grid_columnconfigure(0, weight=1)
    actions_frame.grid_columnconfigure(1, weight=1)
    
    # Shortcut creator button
    btn_shortcut = HoverButton(root, text="✨ Create Desktop App Icon (Shortcut)", bg="#cba6f7", fg="#11111b", font=("Segoe UI", 10, "bold"), bd=0, padx=10, pady=6, active_bg="#f5c2e7", active_fg="#11111b", command=create_desktop_shortcut_gui)
    btn_shortcut.pack(fill="x", padx=25, pady=5)

    # Logs Frame
    logs_frame = tk.LabelFrame(root, text=" Activity Logs ", fg="#f9e2af", bg="#181825", font=("Segoe UI", 9, "bold"), bd=1, relief="solid")
    logs_frame.pack(fill="both", expand=True, padx=20, pady=(5, 10))
    
    log_widget = tk.Text(logs_frame, bg="#11111b", fg="#a6adc8", font=("Consolas", 9), bd=0, state="disabled", highlightthickness=0)
    log_widget.pack(fill="both", expand=True, padx=5, pady=5)
    
    # Start poller thread
    poller_thread = threading.Thread(target=bg_status_poller, daemon=True)
    poller_thread.start()
    
    # Run status updates
    update_gui_status()
    
    log_message("Deela LMS Control Center GUI started successfully.")
    
    def on_closing():
        root.destroy()
        sys.exit(0)
        
    root.protocol("WM_DELETE_WINDOW", on_closing)
    root.mainloop()

def build_gui():
    if HAS_CUSTOMTKINTER:
        build_custom_gui()
    else:
        build_standard_gui()

def run_cli():
    global launch_target
    print("\n" + "="*50)
    print("           DEELA LMS - CONTROL CENTER")
    print("="*50)
    
    # Start status poller thread
    poller_thread = threading.Thread(target=bg_status_poller, daemon=True)
    poller_thread.start()
    time.sleep(0.5)
    
    while True:
        ap_status = "[🟢 RUNNING]" if status_data["apache"] else "[🔴 STOPPED]"
        my_status = "[🟢 RUNNING]" if status_data["mysql"] else "[🔴 STOPPED]"
        rt_status = "[🟢 RUNNING]" if status_data["artisan"] else "[🔴 STOPPED]"
        
        target_str = "🖥️ STANDALONE DESKTOP APP" if launch_target == "desktop" else "🌐 WEB BROWSER"
        
        print(f"\n📢 CURRENT SERVICE STATUS:")
        print(f"   1. Apache Web Server (Port 80):    {ap_status}")
        print(f"   2. MySQL Database Server (Port 3306): {my_status}")
        print(f"   3. Laravel Dev Server (Port 8000):    {rt_status}")
        print("-" * 50)
        print(f"🔧 CURRENT LAUNCH TYPE: {target_str}")
        print("📁 CHOOSE RUNNING MODE & ACTIONS:")
        print("  [1] Launch with XAMPP (Apache + MySQL)")
        print("  [2] Launch without Apache (Artisan Serve + MySQL)")
        print("  [3] Launch Online Cloud Mode (Artisan only - No XAMPP MySQL)")
        print("  [4] Toggle Launch Type (Desktop Standalone <-> Web Browser)")
        print("  [5] Stop All Services")
        print("  [6] Open phpMyAdmin in Browser")
        print("  [7] Create Desktop App Icon (Shortcut)")
        print("  [8] Show Local LAN Web Access URLs")
        print("  [9] Exit")
        print("="*50)
        
        try:
            choice = input("Enter choice (1-9): ").strip()
            if choice == '1':
                launch_application("xampp")
            elif choice == '2':
                launch_application("artisan")
            elif choice == '3':
                launch_application("online")
            elif choice == '4':
                launch_target = "browser" if launch_target == "desktop" else "desktop"
                print(f"Launch target switched to: {'WEB BROWSER' if launch_target == 'browser' else 'STANDALONE DESKTOP APP'}")
            elif choice == '5':
                stop_all_services()
            elif choice == '6':
                print("🌐 Opening phpMyAdmin...")
                webbrowser.open("http://localhost/phpmyadmin")
            elif choice == '7':
                success, details = create_desktop_shortcut()
                if success:
                    print(f"✨ Success: Desktop shortcut created at {details}")
                else:
                    print(f"❌ Error: {details}")
            elif choice == '8':
                local_ip = get_local_ip()
                print(f"\n📶 Local LAN Web Access:")
                print(f"   - Artisan Serve: http://{local_ip}:8000")
                print(f"   - Apache Server: http://{local_ip}/deela/public/")
            elif choice == '9':
                print("Exiting. Goodbye!")
                break
            else:
                print("Invalid choice! Please select 1-9.")
        except KeyboardInterrupt:
            print("\nExiting. Goodbye!")
            break

if __name__ == "__main__":
    parser = argparse.ArgumentParser(description="Deela LMS Application Launcher")
    parser.add_argument("--cli", action="store_true", help="Launch in command-line interface mode")
    parser.add_argument("--gui", action="store_true", help="Launch in graphical user interface mode")
    parser.add_argument("--webview", type=str, help="Launch standalone webview window pointing to URL and exit")
    parser.add_argument("--target", type=str, choices=["desktop", "browser"], default="desktop", help="Default launch target type")
    parser.add_argument("--create-shortcut", action="store_true", help="Create desktop shortcut and exit")
    
    args = parser.parse_args()
    
    if args.webview:
        launch_webview(args.webview)
        sys.exit(0)
        
    launch_target = args.target
    
    if args.create_shortcut:
        success, details = create_desktop_shortcut()
        if success:
            print(f"Desktop shortcut created: {details}")
            sys.exit(0)
        else:
            print(f"Error: {details}")
            sys.exit(1)
            
    if args.cli:
        run_cli()
    elif args.gui:
        build_gui()
    else:
        # If no arguments are passed, we default to GUI mode
        # but fallback to CLI mode if GUI is not supported (e.g. headless)
        try:
            build_gui()
        except Exception as e:
            print(f"GUI could not be initialized: {e}")
            print("Falling back to CLI mode...")
            run_cli()
