import requests
from kivy.uix.boxlayout import BoxLayout
from kivy.uix.label import Label
from kivy.uix.screenmanager import Screen
from kivy.uix.button import Button
from kivy.uix.popup import Popup
from kivy.uix.scrollview import ScrollView
from kivy.uix.gridlayout import GridLayout

API_URL = "https://zofasoftwares.com/demo/api"

class LogsScreen(Screen):
    def __init__(self, **kwargs):
        super().__init__(**kwargs)
        self.layout = BoxLayout(orientation='vertical', padding=10, spacing=10)
        
        # Create a ScrollView
        self.scroll_view = ScrollView(size_hint=(1, 0.9))
        
        # Create a layout to hold the logs inside the ScrollView
        self.logs_list_layout = GridLayout(cols=1, spacing=10, size_hint_y=None)
        self.logs_list_layout.bind(minimum_height=self.logs_list_layout.setter('height'))
        
        self.scroll_view.add_widget(self.logs_list_layout)
        self.layout.add_widget(self.scroll_view)
        
        # Back Button
        self.back_button = Button(text='Back', size_hint_y=None, height=40)
        self.back_button.bind(on_press=self.go_back)
        self.layout.add_widget(self.back_button)
        
        self.add_widget(self.layout)

    def on_enter(self, *args):
        self.load_logs()

    def load_logs(self):
        if not self.manager or not self.manager.token:
            self.show_popup("Error", "No token available.")
            return

        response = requests.post(f"{API_URL}/logs/read/", json={"token": self.manager.token})
        response_data = response.json()
        
        # Print response data to the console
        print("Logs Response Data:", response_data)
        
        if response_data.get("status") == "success":
            logs = response_data.get("data", [])
            # Sort logs in descending order based on 'datetime' if available
            logs.sort(key=lambda x: x.get("datetime", ""), reverse=True)
            
            self.logs_list_layout.clear_widgets()
            for log in logs:
                # Fetch user information based on token
                user_info = self.get_user_info(log.get('token', ''))
                user_name = user_info.get('fullname', 'Unknown') if user_info else 'Unknown'
                
                log_box = BoxLayout(orientation='horizontal', spacing=10)
                log_box.add_widget(Label(text=f"Time: {log.get('datetime', '')}", size_hint_x=None, width=200))
                log_box.add_widget(Label(text=f"User: {user_name}", size_hint_x=None, width=200))
                log_box.add_widget(Label(text=f"Message: {log.get('log', '')}", size_hint_x=None, width=400))
                self.logs_list_layout.add_widget(log_box)
        else:
            self.show_popup("Error", response_data.get("message", "Failed to load logs."))

    def get_user_info(self, token):
        try:
            response = requests.post(f"{API_URL}/users/findtoken/", json={
                "findtoken": token,
                "token": self.manager.token
            })
            response_data = response.json()
            if response_data.get("status") == "success":
                return response_data.get("user", {})
            else:
                print(f"Error fetching user info: {response_data.get('message', 'Unknown error')}")
                return {}
        except Exception as e:
            print(f"Exception occurred: {str(e)}")
            return {}

    def show_popup(self, title, message):
        popup = Popup(title=title, content=Label(text=message), size_hint=(None, None), size=(400, 200))
        popup.open()

    def go_back(self, instance):
        self.manager.current = "dashboard"
