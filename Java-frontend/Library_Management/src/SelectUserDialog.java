import javax.swing.*;
import javax.swing.table.DefaultTableModel;
import java.awt.*;
import java.awt.event.ActionEvent;
import java.io.OutputStream;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.List;

public class SelectUserDialog extends JDialog {
    private JTable userTable;
    private JButton addButton;
    private JButton cancelButton;
    private List<User> users;

    public SelectUserDialog(Frame parent, List<User> users) {
        super(parent, "Select Users to Add as Members", true);
        this.users = users;
        setLayout(new BorderLayout());

        userTable = new JTable();
        DefaultTableModel model = new DefaultTableModel(new Object[]{"ID", "Lastname","Firstname" ,"Email"}, 0);
        for (User user : users) {
            model.addRow(new Object[]{user.getId(), user.getLastname(),  user.getFirstname() , user.getEmail()});
        }
        userTable.setModel(model);
        userTable.setSelectionMode(ListSelectionModel.MULTIPLE_INTERVAL_SELECTION);
        add(new JScrollPane(userTable), BorderLayout.CENTER);

        JPanel buttonPanel = new JPanel();
        addButton = new JButton("Add as Members");
        addButton.addActionListener((ActionEvent e) -> {
            int[] selectedRows = userTable.getSelectedRows();
            for (int row : selectedRows) {
                int userId = (int) model.getValueAt(row, 0);
                try {
                    callApiToAddMember(userId);
                } catch (Exception ex) {
                    JOptionPane.showMessageDialog(SelectUserDialog.this, "Failed to add user with ID: " + userId, "Error", JOptionPane.ERROR_MESSAGE);
                    System.out.println(ex);
                }
            }
            dispose();
        });
        buttonPanel.add(addButton);

        cancelButton = new JButton("Cancel");
        cancelButton.addActionListener((ActionEvent e) -> {
            dispose();
        });
        buttonPanel.add(cancelButton);

        add(buttonPanel, BorderLayout.SOUTH);

        pack();
        setLocationRelativeTo(parent);
    }


    private void callApiToAddMember(int userId) throws Exception {
        String apiUrl = "http://localhost:8000/api/members";
        String payload = "{\"user_id\":" + userId + "}";
        callApiWithTokenPOST(apiUrl, payload);
    }

    private void callApiWithTokenPOST(String apiUrl, String payload) throws Exception {
        URL url = new URL(apiUrl);
        HttpURLConnection conn = (HttpURLConnection) url.openConnection();
        conn.setRequestMethod("POST");
        conn.setRequestProperty("Content-Type", "application/json");

        // Add token in request headers
        String token = UserSession.getToken();
        conn.setRequestProperty("Authorization", "Bearer " + token);

        conn.setDoOutput(true);
        try (OutputStream os = conn.getOutputStream()) {
            byte[] input = payload.getBytes("utf-8");
            os.write(input, 0, input.length);
        }

        int responseCode = conn.getResponseCode();
        if (responseCode != HttpURLConnection.HTTP_CREATED && responseCode != HttpURLConnection.HTTP_OK) {
            throw new Exception("API call failed, response code: " + responseCode);
        }
    }
}