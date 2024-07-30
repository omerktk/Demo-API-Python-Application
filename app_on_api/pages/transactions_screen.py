import hashlib
import requests
from kivy.app import App
from kivy.uix.boxlayout import BoxLayout
from kivy.uix.button import Button
from kivy.uix.textinput import TextInput
from kivy.uix.label import Label
from kivy.uix.screenmanager import Screen
from kivy.uix.popup import Popup
from kivy.uix.tabbedpanel import TabbedPanel, TabbedPanelItem
from kivy.uix.tabbedpanel import TabbedPanel
from datetime import datetime

API_URL = "https://zofasoftwares.com/demo/api"

def generate_token(username, password):
    token_string = f"{username}@{password}"
    return hashlib.sha256(token_string.encode()).hexdigest()

class CustomTabbedPanel(TabbedPanel):
    def __init__(self, **kwargs):
        super().__init__(**kwargs)
        self.tab_height = 60  # Increase tab button height

    def _update_tab_buttons(self):
        for tab in self.tab_list:
            tab.size_hint_y = None
            tab.height = self.tab_height

class TransactionsScreen(Screen):
    def __init__(self, **kwargs):
        super().__init__(**kwargs)

        self.layout = BoxLayout(orientation='vertical', padding=10, spacing=10)

        # Use CustomTabbedPanel instead of TabbedPanel
        self.tabbed_panel = CustomTabbedPanel(do_default_tab=False)
        self.tabbed_panel.size_hint = (1, 1)

        # Transaction List Tab
        self.transaction_list_tab = TabbedPanelItem(text="Transaction List")
        self.transaction_list_layout = BoxLayout(orientation='vertical', padding=10, spacing=10)
        self.transaction_list_tab.add_widget(self.transaction_list_layout)
        self.tabbed_panel.add_widget(self.transaction_list_tab)

        # Transaction Creation Tab
        self.create_transaction_tab = TabbedPanelItem(text="Create Transaction")
        self.create_transaction_layout = BoxLayout(orientation='vertical', padding=10, spacing=10)
        
        self.create_transaction_layout.add_widget(Label(text="From User ID:", size_hint_x=None, width=150))
        self.from_input = TextInput(size_hint_x=None, width=200)
        self.create_transaction_layout.add_widget(self.from_input)
        
        self.create_transaction_layout.add_widget(Label(text="To User ID:", size_hint_x=None, width=150))
        self.to_input = TextInput(size_hint_x=None, width=200)
        self.create_transaction_layout.add_widget(self.to_input)

        self.create_transaction_layout.add_widget(Label(text="Transaction Type:", size_hint_x=None, width=150))
        self.type_input = TextInput(size_hint_x=None, width=200)
        self.create_transaction_layout.add_widget(self.type_input)

        self.create_transaction_layout.add_widget(Label(text="Amount:", size_hint_x=None, width=150))
        self.amount_input = TextInput(size_hint_x=None, width=200)
        self.create_transaction_layout.add_widget(self.amount_input)
        
        self.create_transaction_button = Button(text='Create Transaction', size_hint_y=None, height=40)
        self.create_transaction_button.bind(on_press=self.create_transaction)
        self.create_transaction_layout.add_widget(self.create_transaction_button)
        
        self.create_transaction_tab.add_widget(self.create_transaction_layout)
        self.tabbed_panel.add_widget(self.create_transaction_tab)
        
        self.layout.add_widget(self.tabbed_panel)

        # Back Button
        self.back_button = Button(text='Back', size_hint_y=None, height=40)
        self.back_button.bind(on_press=self.go_back)
        self.layout.add_widget(self.back_button)
        
        self.add_widget(self.layout)

    def on_enter(self, *args):
        self.load_transactions()

    def create_transaction(self, instance):
        from_user_id = self.from_input.text
        to_user_id = self.to_input.text
        transaction_type = self.type_input.text
        amount = self.amount_input.text
        datetime_now = datetime.now().strftime('%Y-%m-%d %H:%M:%S')  # Automatically set datetime

        if not all([from_user_id, to_user_id, transaction_type, amount]):
            self.show_popup("Error", "All fields are required.")
            return
        
        response = requests.post(f"{API_URL}/transactions/create/", json={
            "from": from_user_id,
            "to": to_user_id,
            "type": transaction_type,
            "amount": amount,
            "datetime": datetime_now,
            "token": self.manager.token
        })
        
        response_data = response.json()
        print("Create Transaction Response Data:", response_data)
        
        if response_data.get("status") == "success":
            self.show_popup("Success", "Transaction created successfully.")
            self.load_transactions()
        else:
            self.show_popup("Error", response_data.get("message", "Failed to create transaction."))

    def load_transactions(self):
        if not self.manager or not self.manager.token:
            print("Error: ScreenManager or token is not initialized")
            return

        response = requests.post(f"{API_URL}/transactions/read/", json={"token": self.manager.token})
        response_data = response.json()
        
        # Print response data to the console
        print("Transactions Response Data:", response_data)
        
        if response_data.get("status") == "success":
            transactions = response_data.get("data", [])
            self.transaction_list_layout.clear_widgets()
            for transaction in transactions:
                transaction_box = BoxLayout(orientation='horizontal', spacing=10)
                transaction_box.add_widget(Label(text=f"From: {transaction['from']}", size_hint_x=None, width=150))
                transaction_box.add_widget(Label(text=f"To: {transaction['to']}", size_hint_x=None, width=150))
                transaction_box.add_widget(Label(text=f"Type: {transaction['type']}", size_hint_x=None, width=150))
                transaction_box.add_widget(Label(text=f"Amount: {transaction['amount']}", size_hint_x=None, width=150))
                transaction_box.add_widget(Label(text=f"Date: {transaction['datetime']}", size_hint_x=None, width=200))
                
                edit_button = Button(text='Edit', size_hint_x=None, width=100)
                delete_button = Button(text='Delete', size_hint_x=None, width=100)
                edit_button.bind(on_press=lambda btn, t=transaction: self.edit_transaction(t))
                delete_button.bind(on_press=lambda btn, t=transaction: self.delete_transaction(t))
                
                transaction_box.add_widget(edit_button)
                transaction_box.add_widget(delete_button)
                
                self.transaction_list_layout.add_widget(transaction_box)
        else:
            self.show_popup("Error", response_data.get("message", "Failed to load transactions."))

    def edit_transaction(self, transaction):
        self.edit_popup = Popup(title="Edit Transaction", size_hint=(0.8, 0.6))
        
        layout = BoxLayout(orientation='vertical', padding=10, spacing=10)
        
        self.edit_from_input = TextInput(text=transaction["from"], size_hint_y=None, height=40)
        layout.add_widget(Label(text="From User ID:", size_hint_y=None, height=40))
        layout.add_widget(self.edit_from_input)
        
        self.edit_to_input = TextInput(text=transaction["to"], size_hint_y=None, height=40)
        layout.add_widget(Label(text="To User ID:", size_hint_y=None, height=40))
        layout.add_widget(self.edit_to_input)

        self.edit_type_input = TextInput(text=transaction["type"], size_hint_y=None, height=40)
        layout.add_widget(Label(text="Transaction Type:", size_hint_y=None, height=40))
        layout.add_widget(self.edit_type_input)

        self.edit_amount_input = TextInput(text=transaction["amount"], size_hint_y=None, height=40)
        layout.add_widget(Label(text="Amount:", size_hint_y=None, height=40))
        layout.add_widget(self.edit_amount_input)
        
        self.save_changes_button = Button(text="Save Changes", size_hint_y=None, height=40)
        self.save_changes_button.bind(on_press=lambda btn: self.save_transaction_changes(transaction))
        layout.add_widget(self.save_changes_button)
        
        self.edit_popup.content = layout
        self.edit_popup.open()

    def save_transaction_changes(self, transaction):
        from_user_id = self.edit_from_input.text
        to_user_id = self.edit_to_input.text
        transaction_type = self.edit_type_input.text
        amount = self.edit_amount_input.text

        response = requests.post(f"{API_URL}/transactions/update/", json={
            "id": transaction["id"],
            "from": from_user_id,
            "to": to_user_id,
            "type": transaction_type,
            "amount": amount,
            "token": self.manager.token
        })
        
        response_data = response.json()
        print("Update Transaction Response Data:", response_data)
        
        if response_data.get("status") == "success":
            self.show_popup("Success", "Transaction updated successfully.")
            self.load_transactions()
            self.edit_popup.dismiss()
        else:
            self.show_popup("Error", response_data.get("message", "Failed to update transaction."))

    def delete_transaction(self, transaction):
        response = requests.post(f"{API_URL}/transactions/delete/", json={"id": transaction["id"], "token": self.manager.token})
        response_data = response.json()
        print("Delete Transaction Response Data:", response_data)
        
        if response_data.get("status") == "success":
            self.show_popup("Success", "Transaction deleted successfully.")
            self.load_transactions()
        else:
            self.show_popup("Error", response_data.get("message", "Failed to delete transaction."))

    def show_popup(self, title, message):
        popup = Popup(title=title, content=Label(text=message), size_hint=(None, None), size=(400, 200))
        popup.open()

    def go_back(self, instance):
        self.manager.current = "dashboard"
