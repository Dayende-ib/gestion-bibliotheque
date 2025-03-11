import javax.swing.*;
import java.awt.*;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;

public class BookDetailsDialog extends JDialog {
    private Book book;
    private JLabel titleLabel;
    private JLabel authorLabel;
    private JLabel publicationYearLabel;
    private JLabel isbnLabel;
    private JLabel statusLabel;
    private JButton borrowButton;
    private JButton returnButton;

    public BookDetailsDialog(Frame parent, Book book) {
        super(parent, "Book Details", true);
        this.book = book;
        initComponents();
    }

    private void initComponents() {
        titleLabel = new JLabel("Title: " + book.getTitle());
        authorLabel = new JLabel("Author: " + book.getAuthor());
        publicationYearLabel = new JLabel("Publication Year: " + book.getPublicationYear());
        isbnLabel = new JLabel("ISBN: " + book.getIsbn());
        statusLabel = new JLabel("Status: " + book.getStatus());

        borrowButton = new JButton("Borrow");
        returnButton = new JButton("Return");

        borrowButton.addActionListener(new ActionListener() {
            @Override
            public void actionPerformed(ActionEvent e) {
                // Code pour emprunter le livre
                borrowBook();
            }
        });

        returnButton.addActionListener(new ActionListener() {
            @Override
            public void actionPerformed(ActionEvent e) {
                // Code pour rendre le livre
                returnBook();
            }
        });

        setLayout(new GridLayout(7, 1));
        add(titleLabel);
        add(authorLabel);
        add(publicationYearLabel);
        add(isbnLabel);
        add(statusLabel);
        add(borrowButton);
        add(returnButton);

        pack();
        setLocationRelativeTo(getParent());
    }

    private void borrowBook() {
        // Code pour emprunter le livre
        // Par exemple, appeler une API pour emprunter le livre
        JOptionPane.showMessageDialog(this, "Book borrowed successfully!");
        dispose();
    }

    private void returnBook() {
        // Code pour rendre le livre
        // Par exemple, appeler une API pour rendre le livre
        JOptionPane.showMessageDialog(this, "Book returned successfully!");
        dispose();
    }
}