import hashlib
import requests
from kivy.uix.boxlayout import BoxLayout
from kivy.uix.button import Button
from kivy.uix.textinput import TextInput
from kivy.uix.label import Label
from kivy.uix.screenmanager import Screen
from kivy.uix.popup import Popup
from datetime import datetime

API_URL = "https://zofasoftwares.com/demo/api"

def generate_token(username, password):
    token_string = f"{username}@{password}"
    return hashlib.sha256(token_string.encode()).hexdigest()

def log_action(action_message, token):
    datetime_now = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
    log_data = {
        "log": action_message,
        "datetime": datetime_now,
        "token": token
    }
    try:
        response = requests.post(f"{API_URL}/logs/create/", json=log_data)
        response_data = response.json()
        if response_data.get("status") == "success":
            print("Log recorded successfully.")
        else:
            print(f"Failed to record log: {response_data.get('message')}")
    except Exception as e:
        print(f"Error while recording log: {e}")

class LoginScreen(Screen):
    def __init__(self, **kwargs):
        super().__init__(**kwargs)
        self.layout = BoxLayout(orientation='vertical', padding=10, spacing=10)
        self.username_input = TextInput(hint_text='Username', size_hint_y=None, height=40)
        self.password_input = TextInput(hint_text='Password', password=True, size_hint_y=None, height=40)
        self.login_button = Button(text='Login', size_hint_y=None, height=40)
        self.login_button.bind(on_press=self.login)
        self.layout.add_widget(self.username_input)
        self.layout.add_widget(self.password_input)
        self.layout.add_widget(self.login_button)
        self.add_widget(self.layout)

    def login(self, instance):
        username = self.username_input.text
        password = self.password_input.text
        token = generate_token(username, password)
        response = requests.post(f"{API_URL}/auth/", json={"token": token})
        if response.json().get("status") == "success":
            self.manager.token = token
            log_action(f"User {username} logged in successfully.", token)
            self.manager.current = "dashboard"
        else:
            self.show_popup("Login Failed", "Invalid username or password.")


    def show_popup(self, title, message):
        popup = Popup(title=title, content=Label(text=message), size_hint=(None, None), size=(400, 200))
        popup.open()
 
