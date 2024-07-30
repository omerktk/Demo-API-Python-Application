import hashlib
import requests
from kivy.uix.boxlayout import BoxLayout
from kivy.uix.button import Button
from kivy.uix.textinput import TextInput
from kivy.uix.label import Label
from kivy.uix.screenmanager import Screen
from kivy.uix.popup import Popup
from kivy.uix.tabbedpanel import TabbedPanel, TabbedPanelItem

API_URL = "https://zofasoftwares.com/demo/api"

def generate_token(username, password):
    token_string = f"{username}@{password}"
    return hashlib.sha256(token_string.encode()).hexdigest()

class UsersScreen(Screen):
    def __init__(self, **kwargs):
        super().__init__(**kwargs)
        
        self.layout = BoxLayout(orientation='vertical', padding=10, spacing=10)
        
        # Tab Layout
        self.tabbed_panel = TabbedPanel(do_default_tab=False)
        self.tabbed_panel.size_hint = (1, 1)

        # User List Tab
        self.user_list_tab = TabbedPanelItem(text="User List")
        self.user_list_layout = BoxLayout(orientation='vertical', padding=10, spacing=10)
        self.user_list_tab.add_widget(self.user_list_layout)
        self.tabbed_panel.add_widget(self.user_list_tab)
        
        # User Creation Tab
        self.user_create_tab = TabbedPanelItem(text="Create User")
        self.user_create_layout = BoxLayout(orientation='vertical', padding=10, spacing=10)
        
        self.user_create_layout.add_widget(Label(text="Username:", size_hint_x=None, width=100))
        self.username_input = TextInput(size_hint_x=None, width=200)
        self.user_create_layout.add_widget(self.username_input)
        
        self.user_create_layout.add_widget(Label(text="Password:", size_hint_x=None, width=100))
        self.password_input = TextInput(password=True, size_hint_x=None, width=200)
        self.user_create_layout.add_widget(self.password_input)

        self.user_create_layout.add_widget(Label(text="Full Name:", size_hint_x=None, width=100))
        self.fullname_input = TextInput(size_hint_x=None, width=200)
        self.user_create_layout.add_widget(self.fullname_input)
        
        self.create_user_button = Button(text='Create User', size_hint_y=None, height=40)
        self.create_user_button.bind(on_press=self.create_user)
        self.user_create_layout.add_widget(self.create_user_button)
        
        self.user_create_tab.add_widget(self.user_create_layout)
        self.tabbed_panel.add_widget(self.user_create_tab)
        
        self.layout.add_widget(self.tabbed_panel)
        
        # Back Button
        self.back_button = Button(text='Back', size_hint_y=None, height=40)
        self.back_button.bind(on_press=self.go_back)
        self.layout.add_widget(self.back_button)
        
        self.add_widget(self.layout)

    def on_enter(self, *args):
        self.load_users()

    def load_users(self):
        if not self.manager or not self.manager.token:
            print("Error: ScreenManager or token is not initialized")
            return

        response = requests.post(f"{API_URL}/users/read/", json={"token": self.manager.token})
        response_data = response.json()
        
        # Print response data to the console
        print("Users Response Data:", response_data)
        
        if response_data.get("status") == "success":
            users = response_data.get("data", [])  # Updated to match API response
            self.user_list_layout.clear_widgets()
            for user in users:
                user_box = BoxLayout(orientation='horizontal', spacing=10)
                user_box.add_widget(Label(text=user["fullname"], size_hint_x=None, width=300))
                edit_button = Button(text='Edit', size_hint_x=None, width=100)
                delete_button = Button(text='Delete', size_hint_x=None, width=100)
                edit_button.bind(on_press=lambda btn, u=user: self.edit_user(u))
                delete_button.bind(on_press=lambda btn, u=user: self.delete_user(u))
                user_box.add_widget(edit_button)
                user_box.add_widget(delete_button)
                self.user_list_layout.add_widget(user_box)
        else:
            self.show_popup("Error", response_data.get("message", "Failed to load users."))

    def create_user(self, instance):
        username = self.username_input.text
        password = self.password_input.text
        fullname = self.fullname_input.text
        new_token = generate_token(username, password)
        
        response = requests.post(f"{API_URL}/users/create/", json={
            "fullname": fullname,
            "newtoken": new_token,
            "token": self.manager.token
        })
        
        response_data = response.json()
        print("Create User Response Data:", response_data)
        
        if response_data.get("status") == "success":
            self.show_popup("Success", "User created successfully.")
            self.load_users()
        else:
            self.show_popup("Error", response_data.get("message", "Failed to create user."))

    def edit_user(self, user):
        # Show the edit form in a popup
        self.edit_popup = Popup(title="Edit User", size_hint=(0.8, 0.6))
        
        layout = BoxLayout(orientation='vertical', padding=10, spacing=10)
        
        self.new_fullname_input = TextInput(text=user["fullname"], size_hint_y=None, height=40)
        layout.add_widget(Label(text="Full Name:", size_hint_y=None, height=40))
        layout.add_widget(self.new_fullname_input)
        
        self.save_changes_button = Button(text="Save Changes", size_hint_y=None, height=40)
        self.save_changes_button.bind(on_press=lambda btn: self.save_user_changes(user))
        layout.add_widget(self.save_changes_button)
        
        self.edit_popup.content = layout
        self.edit_popup.open()

    def save_user_changes(self, user):
        new_fullname = self.new_fullname_input.text
        
        response = requests.post(f"{API_URL}/users/update/", json={
            "id": user["id"],
            "fullname": new_fullname,
            "token": self.manager.token
        })
        
        response_data = response.json()
        print("Update User Response Data:", response_data)
        
        if response_data.get("status") == "success":
            self.show_popup("Success", "User updated successfully.")
            self.load_users()
            self.edit_popup.dismiss()
        else:
            self.show_popup("Error", response_data.get("message", "Failed to update user."))

    def delete_user(self, user):
        response = requests.post(f"{API_URL}/users/delete/", json={"id": user["id"], "token": self.manager.token})
        response_data = response.json()
        print("Delete User Response Data:", response_data)
        
        if response_data.get("status") == "success":
            self.show_popup("Success", "User deleted successfully.")
            self.load_users()
        else:
            self.show_popup("Error", response_data.get("message", "Failed to delete user."))

    def show_popup(self, title, message):
        popup = Popup(title=title, content=Label(text=message), size_hint=(None, None), size=(400, 200))
        popup.open()

    def go_back(self, instance):
        self.manager.current = "dashboard"
 
