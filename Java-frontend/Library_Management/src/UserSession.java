/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Classes/Class.java to edit this template
 */

/**
 *
 * @author IBRAHIM DAYENDE
 */
public class UserSession {
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
