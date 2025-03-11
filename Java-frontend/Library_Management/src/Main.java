

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Main.java to edit this template
 */

/**
 *
 * @author IBRAHIM DAYENDE
 */
public class Main {

    public static void main(String[] args) {
        if (isUserLoggedIn()) {
            launchMainPage();
        } else {
            launchLoginPage();
        }
    }

    private static boolean isUserLoggedIn() {
        // Exemple : vérifier la présence d'un token dans les préférences ou un fichier
        String token = UserSession.getToken();
        return token != null && !token.isEmpty();
    }

    private static void launchMainPage() {
        java.awt.EventQueue.invokeLater(() -> new dashboard().setVisible(true));
    }

    private static void launchLoginPage() {
        java.awt.EventQueue.invokeLater(() -> {
            login loginn = new login();
            loginn.setVisible(true);
            loginn.addLoginListener(token -> {
                UserSession.setToken(token);
                launchMainPage();
            });
        });
    }
    // Interface pour écouter les événements de connexion

}