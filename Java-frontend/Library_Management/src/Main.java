
import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Main.java to edit this template
 */

/**
 *
 * @author IBRAHIM DAYENDE
 */
public class Main {

    /**
     * @param args the command line arguments
     */
    @SuppressWarnings("CallToPrintStackTrace")
    public static void main(String[] args) {

        if (isUserLoggedIn()) {
            launchMainPage();
        } else {
            launchLoginPage();
        }

        // Exemple d'appel à une API avec le token de connexion
        try {
            String response = callApiWithToken("http://localhost:8000/api/user");
            System.out.println("Réponse de l'API: " + response);
        } catch (Exception e) {
            e.printStackTrace();
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
            login login = new login();
            login.setVisible(true);
            login.addLoginListener(new LoginListener() {
                @Override
                public void onLoginSuccess(String token) {
                    UserSession.setToken(token);
                    launchMainPage();
                }
            });
        });
    }

    private static String callApiWithToken(String apiUrl) throws Exception {
        URL url = new URL(apiUrl);
        HttpURLConnection conn = (HttpURLConnection) url.openConnection();
        conn.setRequestMethod("GET");

        // Ajouter le token dans les en-têtes de la requête
        String token = UserSession.getToken();
        conn.setRequestProperty("Authorization", "Bearer " + token);

        int responseCode = conn.getResponseCode();
        if (responseCode == HttpURLConnection.HTTP_OK) {
            BufferedReader in = new BufferedReader(new InputStreamReader(conn.getInputStream()));
            String inputLine;
            StringBuilder response = new StringBuilder();

            while ((inputLine = in.readLine()) != null) {
                response.append(inputLine);
            }
            in.close();

            return response.toString();
        } else {
            throw new Exception("Échec de l'appel à l'API, code de réponse: " + responseCode);
        }
    }

    // Exemple de gestion de session
    static class UserSession {
        private static String token;

        public static void setToken(String authToken) {
            token = authToken;
        }

        public static String getToken() {
            return token;
        }

        public static void clearSession() {
            token = null;
        }
    }

    // Interface pour écouter les événements de connexion
    interface LoginListener {
        void onLoginSuccess(String token);
    }
    
}
