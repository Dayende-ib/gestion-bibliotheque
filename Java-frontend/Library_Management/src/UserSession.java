/**
 *
 * @author IBRAHIM DAYENDE
 */
import java.util.prefs.Preferences;

public class UserSession {
    private static final String TOKEN_KEY = "authToken";
    private static Preferences prefs = Preferences.userNodeForPackage(UserSession.class);

    public static void setToken(String token) {
        prefs.put(TOKEN_KEY, token);
    }

    public static String getToken() {
        return prefs.get(TOKEN_KEY, null);
    }

    public static void clearToken() {
        prefs.remove(TOKEN_KEY);
    }
}
