import javax.swing.*;
import java.awt.*;
import java.awt.event.ActionEvent;
import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;


public class BookDetailsDialog extends JDialog {
    private Book book;
    private JLabel titleLabel;
    private JLabel authorLabel;
    private JLabel publicationYearLabel;
    private JLabel isbnLabel;
    private JLabel statusLabel;
    private JButton borrowButton;
    private JButton returnButton;
    private JTextArea descriptionArea;
    private dashboard dashboard;

    public BookDetailsDialog(Frame parent, Book book, dashboard dashboard) {
        this.dashboard = dashboard;
        super(parent, "Book Details", true);
        this.book = book;
        initComponents();
    }

    void initComponents() {
        titleLabel = new JLabel("Title: " + book.getTitle());
        authorLabel = new JLabel("Author: " + book.getAuthor());
        publicationYearLabel = new JLabel("Publication Year: " + book.getPublicationYear());
        isbnLabel = new JLabel("ISBN: " + book.getIsbn());
        statusLabel = new JLabel("Status: " + book.getStatus());
        descriptionArea = new JTextArea();
        descriptionArea.setEditable(false);
        descriptionArea.setLineWrap(true);
        descriptionArea.setWrapStyleWord(true);
        descriptionArea.setText(book.getDescription() != null ? book.getDescription() : "Aucune description disponible");

        borrowButton = new JButton("Borrow");
        returnButton = new JButton("Return");

        borrowButton.addActionListener((ActionEvent e) -> {
            // Code pour emprunter le livre
            borrowBook();
        });

        returnButton.addActionListener((ActionEvent e) -> {
            // Code pour rendre le livre
            returnBook();
        });

        setLayout(new GridLayout(8, 1));
        add(titleLabel);
        add(authorLabel);
        add(publicationYearLabel);
        add(isbnLabel);
        add(descriptionArea);
        add(statusLabel);
        add(borrowButton);
        add(returnButton);

        setPreferredSize(new Dimension(500, 600));
        pack();
        setLocationRelativeTo(getParent());
    }

    private void borrowBook() {
        try {
            // Create API URL
            String url = "http://localhost:8000/api/books/borrow/" + book.getId();

            // Create HTTP connection
            HttpURLConnection connection = (HttpURLConnection) new URL(url).openConnection();
            connection.setRequestMethod("POST");
            connection.setRequestProperty("Content-Type", "application/json");
            connection.setRequestProperty("Accept", "application/json");
            connection.setDoOutput(true);

            String token = UserSession.getToken();
            connection.setRequestProperty("Authorization", "Bearer " + token);

            // Send request
            int responseCode = connection.getResponseCode();

            if (responseCode == HttpURLConnection.HTTP_OK) {
                StringBuilder response;
                try (BufferedReader in = new BufferedReader(new InputStreamReader(connection.getInputStream()))) {
                    String inputLine;
                    response = new StringBuilder();
                    while ((inputLine = in.readLine()) != null) {
                        response.append(inputLine);
                        System.out.println(response);
                    }
                }

                System.out.println(response);
                JOptionPane.showMessageDialog(this, "Book borrowed successfully!");
                dashboard.refreshTable();
                dispose();

            } else {
                JOptionPane.showMessageDialog(this, "Failed to borrow book", "Error: " + responseCode, JOptionPane.ERROR_MESSAGE);
            }
        } catch (HeadlessException | IOException e) {
            JOptionPane.showMessageDialog(this, "Error: " + e.getMessage(), "Error", JOptionPane.ERROR_MESSAGE);
        }

    }

    private void returnBook() {
        try {
            // Create API URL
            String url = "http://localhost:8000/api/books/return/" + book.getId();

            // Create HTTP connection
            HttpURLConnection connection = (HttpURLConnection) new URL(url).openConnection();
            connection.setRequestMethod("POST");
            connection.setRequestProperty("Content-Type", "application/json");
            connection.setRequestProperty("Accept", "application/json");
            connection.setDoOutput(true);

            String token = UserSession.getToken();
            connection.setRequestProperty("Authorization", "Bearer " + token);

            // Send request
            int responseCode = connection.getResponseCode();

            if (responseCode == HttpURLConnection.HTTP_OK) {
                JOptionPane.showMessageDialog(this, "Book returned successfully!");
                dispose();
            } else {
                JOptionPane.showMessageDialog(this, "Failed to return book", "Error", JOptionPane.ERROR_MESSAGE);
            }
        } catch (Exception e) {
            JOptionPane.showMessageDialog(this, "Error: " + e.getMessage(), "Error", JOptionPane.ERROR_MESSAGE);
        }
    }
    
    
}