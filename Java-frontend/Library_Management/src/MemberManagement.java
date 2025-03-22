import java.io.OutputStream;
import java.net.HttpURLConnection;
import java.net.URL;
import java.nio.charset.StandardCharsets;
import javax.swing.JButton;
import javax.swing.JOptionPane;
import javax.swing.JFrame;
import javax.swing.JPanel;
import javax.swing.GroupLayout;

/**
 *
 * @author IBRAHIM DAYENDE
 */
public class MemberManagement extends JFrame {

    // Form fields for member management
    private javax.swing.JTextField inputPhone;
    private javax.swing.JTextField inputAddress;
    private javax.swing.JTextField inputMembershipNumber;
    private javax.swing.JTextField inputJoinDate;
    private javax.swing.JTextField inputExpiryDate;
    private JButton jButtonSave;

    public MemberManagement() {
        initComponents();
    }

    private void initComponents() {
        inputPhone = new javax.swing.JTextField();
        inputAddress = new javax.swing.JTextField();
        inputMembershipNumber = new javax.swing.JTextField();
        inputJoinDate = new javax.swing.JTextField();
        inputExpiryDate = new javax.swing.JTextField();
        jButtonSave = new JButton("Save");

        // Add action listener for the save button
        jButtonSave.addActionListener(evt -> saveMember());

        // Layout code to arrange components
        JPanel panel = new JPanel();
        GroupLayout layout = new GroupLayout(panel);
        panel.setLayout(layout);
        layout.setAutoCreateGaps(true);
        layout.setAutoCreateContainerGaps(true);

        layout.setHorizontalGroup(layout.createSequentialGroup()
            .addGroup(layout.createParallelGroup(GroupLayout.Alignment.LEADING)
                .addComponent(inputPhone)
                .addComponent(inputAddress)
                .addComponent(inputMembershipNumber)
                .addComponent(inputJoinDate)
                .addComponent(inputExpiryDate)
                .addComponent(jButtonSave))
        );

        layout.setVerticalGroup(layout.createSequentialGroup()
            .addComponent(inputPhone)
            .addComponent(inputAddress)
            .addComponent(inputMembershipNumber)
            .addComponent(inputJoinDate)
            .addComponent(inputExpiryDate)
            .addComponent(jButtonSave)
        );

        this.add(panel);
        this.pack();
        this.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
    }

    private void saveMember() {
        String phone = inputPhone.getText().trim();
        if (phone.isEmpty()) {
            JOptionPane.showMessageDialog(this, "Phone field cannot be empty.", "Error", JOptionPane.ERROR_MESSAGE);
            return;
        }
        String address = inputAddress.getText();
        String membershipNumber = inputMembershipNumber.getText();
        String joinDate = inputJoinDate.getText();
        String expiryDate = inputExpiryDate.getText();

        try {
            // URL of the API
            URL url = new URL("http://127.0.0.1:8000/api/members");
            HttpURLConnection conn = (HttpURLConnection) url.openConnection();
            conn.setRequestMethod("POST");
            conn.setRequestProperty("Content-Type", "application/json; utf-8");
            conn.setRequestProperty("Accept", "application/json");
            conn.setDoOutput(true);

            // Request body
            String jsonInputString = String.format(
                "{\"phone\": \"%s\", \"address\": \"%s\", \"membership_number\": \"%s\", \"join_date\": \"%s\", \"expiry_date\": \"%s\"}",
                phone, address, membershipNumber, joinDate, expiryDate
            );

            // Send the request
            try (OutputStream os = conn.getOutputStream()) {
                byte[] input = jsonInputString.getBytes(StandardCharsets.UTF_8);
                os.write(input, 0, input.length);
            }

            // Handle response
            int code = conn.getResponseCode();
            if (code == 201) {
                JOptionPane.showMessageDialog(this, "Member created successfully!");
            } else {
                JOptionPane.showMessageDialog(this, "Failed to create member. Response code: " + code);
            }
        } catch (Exception e) {
            e.printStackTrace();
            JOptionPane.showMessageDialog(this, "An error occurred: " + e.getMessage());
        }
    }

    public static void main(String args[]) {
        java.awt.EventQueue.invokeLater(() -> new MemberManagement().setVisible(true));
    }
}
