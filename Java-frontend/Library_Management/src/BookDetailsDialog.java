import javax.swing.*;  
import java.awt.*;  
import java.awt.event.ActionEvent;  
import java.io.IOException;  
import java.net.HttpURLConnection;  
import java.net.URL;  
import java.awt.image.BufferedImage;  
import javax.imageio.ImageIO;  

public class BookDetailsDialog extends JDialog {  
    private Book book;  
    private JLabel titleLabel, authorLabel, publicationYearLabel, isbnLabel, statusLabel, imageLabel;  
    private JButton borrowButton, returnButton;  
    private JTextArea descriptionArea;  
    private dashboard dashboard;  

    public BookDetailsDialog(Frame parent, Book book, dashboard dashboard) {  
        super(parent, "Book Details", true);  
        this.book = book;  
        this.dashboard = dashboard;  
        initComponents();  
    }  

    private void initComponents() {  
        // Titre
        titleLabel = new JLabel("<html><body style='width: 300px;'><b>📖 " + book.getTitle() + "</b></body></html>");  
        titleLabel.setFont(new Font("Arial", Font.BOLD, 16));
 

        // Informations du livre
        authorLabel = new JLabel("✍️ Author: " + book.getAuthor());  
        publicationYearLabel = new JLabel("📅 Year: " + book.getPublicationYear());  
        isbnLabel = new JLabel("🔢 ISBN: " + book.getIsbn());  
        statusLabel = new JLabel("🟢 Status: " + book.getStatus());  

        // Description avec un JScrollPane
        descriptionArea = new JTextArea(5, 30);  
        descriptionArea.setEditable(false);  
        descriptionArea.setLineWrap(true);  
        descriptionArea.setWrapStyleWord(true);  
        descriptionArea.setText(book.getDescription() != null ? book.getDescription() : "No description available");  
        JScrollPane descriptionScroll = new JScrollPane(descriptionArea);  

        // Chargement de l'image
        imageLabel = new JLabel();  
        loadBookImage(book.getImagePath());  

        // Boutons
        borrowButton = new JButton("📥 Borrow");  
        returnButton = new JButton("📤 Return");  

        borrowButton.addActionListener((ActionEvent e) -> borrowBook());  
        returnButton.addActionListener((ActionEvent e) -> returnBook());  

        // Désactiver les boutons selon l'état du livre
        if (book.getStatus().equalsIgnoreCase("Borrowed")) {
            borrowButton.setEnabled(false);
        } else {
            returnButton.setEnabled(false);
        }

        // Layout principal
        setLayout(new BorderLayout());  

        // Panel principal avec GridBagLayout pour un meilleur positionnement
        JPanel mainPanel = new JPanel(new GridBagLayout());  
        GridBagConstraints gbc = new GridBagConstraints();  
        gbc.gridx = 0;  
        gbc.gridy = 0;  
        gbc.gridwidth = 2;  
        gbc.insets = new Insets(10, 10, 10, 10);  

        mainPanel.add(titleLabel, gbc);  

        gbc.gridy++;  
        mainPanel.add(imageLabel, gbc);  

        gbc.gridwidth = 1;  
        gbc.gridy++;  
        gbc.anchor = GridBagConstraints.WEST;  
        mainPanel.add(authorLabel, gbc);  

        gbc.gridy++;  
        mainPanel.add(publicationYearLabel, gbc);  

        gbc.gridy++;  
        mainPanel.add(isbnLabel, gbc);  

        gbc.gridy++;  
        mainPanel.add(statusLabel, gbc);  

        gbc.gridy++;  
        gbc.gridwidth = 2;  
        mainPanel.add(descriptionScroll, gbc);  

        // Panel pour les boutons
        JPanel buttonPanel = new JPanel();  
        buttonPanel.add(borrowButton);  
        buttonPanel.add(returnButton);  

        add(mainPanel, BorderLayout.CENTER);  
        add(buttonPanel, BorderLayout.SOUTH);  

        setPreferredSize(new Dimension(450, 700));  
        pack();  
        setLocationRelativeTo(getParent());  
    }  

    private void loadBookImage(String imageUrl) {  
        try {  
            if (imageUrl == null || imageUrl.isEmpty()) {
                throw new IOException("Image path is empty");
            }
            URL url = new URL(imageUrl);  
            BufferedImage img = ImageIO.read(url);  
            ImageIcon icon = new ImageIcon(img.getScaledInstance(200, 300, Image.SCALE_SMOOTH));  
            imageLabel.setIcon(icon);  
        } catch (IOException e) {  
            imageLabel.setText("📷 No Image Available");  
        }  
    }  

    private void borrowBook() {  
        sendRequest("http://localhost:8000/api/books/borrow/" + book.getId(), "Book borrowed successfully!", "Failed to borrow book");  
    }  

    private void returnBook() {  
        sendRequest("http://localhost:8000/api/books/return/" + book.getId(), "Book returned successfully!", "Failed to return book");  
    }  

    private void sendRequest(String url, String successMessage, String errorMessage) {  
        try {  
            HttpURLConnection connection = (HttpURLConnection) new URL(url).openConnection();  
            connection.setRequestMethod("POST");  
            connection.setRequestProperty("Content-Type", "application/json");  
            connection.setRequestProperty("Accept", "application/json");  
            connection.setRequestProperty("Authorization", "Bearer " + UserSession.getToken());  
            connection.setDoOutput(true);  

            int responseCode = connection.getResponseCode();  

            if (responseCode == HttpURLConnection.HTTP_OK) {  
                JOptionPane.showMessageDialog(this, successMessage);  
                dashboard.refreshTable();  
                dispose();  
            } else {  
                JOptionPane.showMessageDialog(this, errorMessage, "Error: " + responseCode, JOptionPane.ERROR_MESSAGE);  
            }  
        } catch (Exception e) {  
            JOptionPane.showMessageDialog(this, "Error: " + e.getMessage(), "Error", JOptionPane.ERROR_MESSAGE);  
        }  
    }  
}
