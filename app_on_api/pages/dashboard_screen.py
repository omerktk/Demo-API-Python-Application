from kivy.uix.boxlayout import BoxLayout
from kivy.uix.button import Button
from kivy.uix.screenmanager import Screen

class DashboardScreen(Screen):
    def __init__(self, **kwargs):
        super().__init__(**kwargs)
        self.layout = BoxLayout(orientation='vertical', padding=10, spacing=10)
        self.users_button = Button(text='Users', size_hint_y=None, height=40)
        self.transactions_button = Button(text='Transactions', size_hint_y=None, height=40)
        self.logs_button = Button(text='Logs', size_hint_y=None, height=40)
        self.logout_button = Button(text='Logout', size_hint_y=None, height=40)
        
        self.users_button.bind(on_press=self.go_to_users)
        self.transactions_button.bind(on_press=self.go_to_transactions)
        self.logs_button.bind(on_press=self.go_to_logs)
        self.logout_button.bind(on_press=self.logout)

        self.layout.add_widget(self.users_button)
        self.layout.add_widget(self.transactions_button)
        self.layout.add_widget(self.logs_button)
        self.layout.add_widget(self.logout_button)
        self.add_widget(self.layout)

    def go_to_users(self, instance):
        self.manager.current = "users"

    def go_to_transactions(self, instance):
        self.manager.current = "transactions"

    def go_to_logs(self, instance):
        self.manager.current = "logs"

    def logout(self, instance):
        self.manager.current = "login"
        self.manager.token = ""
 
