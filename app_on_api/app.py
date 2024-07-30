from kivy.app import App
from kivy.uix.screenmanager import ScreenManager, FadeTransition
from kivy.uix.boxlayout import BoxLayout
from kivy.uix.button import Button
from kivy.uix.label import Label

# Import other screens
from pages.logs_screen import LogsScreen
from pages.dashboard_screen import DashboardScreen
from pages.users_screen import UsersScreen
from pages.transactions_screen import TransactionsScreen
from pages.login_screen import LoginScreen

class MyApp(App):
    def build(self):
        sm = ScreenManager(transition=FadeTransition())
        sm.add_widget(LoginScreen(name='login'))
        sm.add_widget(DashboardScreen(name='dashboard'))
        sm.add_widget(UsersScreen(name='users'))
        sm.add_widget(TransactionsScreen(name='transactions'))
        sm.add_widget(LogsScreen(name='logs'))
        return sm

if __name__ == '__main__':
    MyApp().run()
