/**
 *
 * @author IBRAHIM DAYENDE
 */

import java.awt.event.MouseAdapter;
import java.awt.event.MouseEvent;
import javax.swing.*;
import javax.swing.table.DefaultTableModel;
import javax.swing.table.TableRowSorter;
import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.ArrayList;
import java.util.List;
import org.json.JSONArray;
import org.json.JSONObject;

public class dashboard extends javax.swing.JFrame {

    private TableRowSorter<DefaultTableModel> rowSorter;

    /**
     * Creates new form dashboard
     */
    public dashboard() {
        initComponents();
        rowSorter = new TableRowSorter<>((DefaultTableModel) bookTable.getModel());
        bookTable.setRowSorter(rowSorter);
        loadBooks();
        loadLoans();
        searchField.getDocument().addDocumentListener(new javax.swing.event.DocumentListener() {
            @Override
            public void insertUpdate(javax.swing.event.DocumentEvent e) {
                String text = searchField.getText();
                if (text.trim().length() == 0) {
                    rowSorter.setRowFilter(null);
                } else {
                    rowSorter.setRowFilter(RowFilter.regexFilter("(?i)" + text));
                }
            }
    
            @Override
            public void removeUpdate(javax.swing.event.DocumentEvent e) {
                String text = searchField.getText();
                if (text.trim().length() == 0) {
                    rowSorter.setRowFilter(null);
                } else {
                    rowSorter.setRowFilter(RowFilter.regexFilter("(?i)" + text));
                }
            }
    
            @Override
            public void changedUpdate(javax.swing.event.DocumentEvent e) {
                throw new UnsupportedOperationException("Not supported yet.");
            }
        });
        
        bookTable.addMouseListener(new MouseAdapter() {
            @Override
            public void mouseClicked(MouseEvent e) {
                if (e.getClickCount() == 2) {
                    int selectedRow = bookTable.getSelectedRow();
                    if (selectedRow != -1) {
                        DefaultTableModel model = (DefaultTableModel) bookTable.getModel();
                        int id = (int) model.getValueAt(selectedRow, 0);
                        String title = (String) model.getValueAt(selectedRow, 1);
                        String author = (String) model.getValueAt(selectedRow, 2);
                        int publicationYear = (int) model.getValueAt(selectedRow, 3);
                        String isbn = (String) model.getValueAt(selectedRow, 4);
                        String status = (String) model.getValueAt(selectedRow, 5);
                        String description = (String) model.getValueAt(selectedRow, 6);
                        
                        Book book = new Book();
                        book.setId(id);
                        book.setTitle(title);
                        book.setAuthor(author);
                        book.setPublicationYear(publicationYear);
                        book.setIsbn(isbn);
                        book.setStatus(status);
                        book.setDescription(description);

                        BookDetailsDialog dialog = new BookDetailsDialog(dashboard.this, book, dashboard.this);
                        dialog.setVisible(true);
                    }
                }
            }
        });
    }
    
    private void launchLoginPage() {
        java.awt.EventQueue.invokeLater(() -> new login().setVisible(true));
    }
    
    private void returnBook(int bookId) {
        if (bookId <= 0) {
            JOptionPane.showMessageDialog(
                this,
                "Invalid book ID",
                "Error",
                JOptionPane.ERROR_MESSAGE
            );
            return;
        }

        int confirm = JOptionPane.showConfirmDialog(
            this,
            "Are you sure you want to return this book?",
            "Confirm Return",
            JOptionPane.YES_NO_OPTION
        );
        
        if (confirm == JOptionPane.YES_OPTION) {
            try {
                // Create JSON payload with book ID
                JSONObject payload = new JSONObject();
                payload.put("id", bookId);

                // Make POST request with payload
                String response = callApiWithTokenPOST("http://localhost:8000/api/books/return/" + bookId, payload.toString());
                
                JSONObject jsonResponse = new JSONObject(response);
                
                if (jsonResponse.has("message")) {
                    JOptionPane.showMessageDialog(
                        this,
                        jsonResponse.getString("message"),
                        "Success",
                        JOptionPane.INFORMATION_MESSAGE
                    );
                } else {
                    JOptionPane.showMessageDialog(
                        this,
                        "Book returned successfully",
                        "Success",
                        JOptionPane.INFORMATION_MESSAGE
                    );
                }
                
                loadLoans(); // Refresh loans after return
            } catch (Exception e) {
                String errorMessage = "Failed to return book";
                if (e.getMessage() != null && !e.getMessage().isEmpty()) {
                    errorMessage += ": " + e.getMessage();
                }
                JOptionPane.showMessageDialog(
                    this,
                    errorMessage,
                    "Error",
                    JOptionPane.ERROR_MESSAGE
                );
            }
        }
    }

    private String callApiWithTokenPOST(String apiUrl, String payload) throws Exception {
        URL url = new URL(apiUrl);
        HttpURLConnection conn = (HttpURLConnection) url.openConnection();
        conn.setRequestMethod("POST");
        conn.setRequestProperty("Content-Type", "application/json");
        conn.setDoOutput(true);

        // Add token in request headers
        String token = UserSession.getToken();
        conn.setRequestProperty("Authorization", "Bearer " + token);

        // Write payload to request body
        try (OutputStream os = conn.getOutputStream()) {
            byte[] input = payload.getBytes("utf-8");
            os.write(input, 0, input.length);
        }

        int responseCode = conn.getResponseCode();
        if (responseCode == HttpURLConnection.HTTP_OK) {
            StringBuilder response;
            try (BufferedReader in = new BufferedReader(new InputStreamReader(conn.getInputStream()))) {
                String inputLine;
                response = new StringBuilder();
                while ((inputLine = in.readLine()) != null) {
                    response.append(inputLine);
                }
            }
            return response.toString();
        } else {
            throw new Exception("Échec de l'appel à l'API, code de réponse: " + responseCode);
        }
    }

    /**
     * This method is called from within the constructor to initialize the form.
     * WARNING: Do NOT modify this code. The content of this method is always
     * regenerated by the Form Editor.
     */
    @SuppressWarnings("unchecked")
    // <editor-fold defaultstate="collapsed" desc="Generated Code">//GEN-BEGIN:initComponents
    private void initComponents() {

        container_tabbed = new javax.swing.JTabbedPane();
        jPanelDashboard = new javax.swing.JPanel();
        jLabel1 = new javax.swing.JLabel();
        jLabel6 = new javax.swing.JLabel();
        jLabel7 = new javax.swing.JLabel();
        jPanelBook = new javax.swing.JPanel();
        jScrollPane1 = new javax.swing.JScrollPane();
        bookTable = new javax.swing.JTable();
        addBookButton = new javax.swing.JButton();
        jLabel2 = new javax.swing.JLabel();
        jPanel1 = new javax.swing.JPanel();
        searchField = new javax.swing.JTextField();
        jLabel3 = new javax.swing.JLabel();
        jPanel2 = new javax.swing.JPanel();
        jScrollPane2 = new javax.swing.JScrollPane();
        TableLoan = new javax.swing.JTable();
        jLabel5 = new javax.swing.JLabel();
        returnBookButton = new javax.swing.JButton();
        jPanel3 = new javax.swing.JPanel();
        jPanel_logout = new javax.swing.JPanel();
        jLabel4 = new javax.swing.JLabel();
        jButton1 = new javax.swing.JButton();

        setDefaultCloseOperation(javax.swing.WindowConstants.EXIT_ON_CLOSE);

        container_tabbed.setBackground(new java.awt.Color(255, 255, 255));
        container_tabbed.setForeground(new java.awt.Color(0, 204, 153));
        container_tabbed.setTabPlacement(javax.swing.JTabbedPane.LEFT);
        container_tabbed.setAutoscrolls(true);
        container_tabbed.setCursor(new java.awt.Cursor(java.awt.Cursor.DEFAULT_CURSOR));
        container_tabbed.setDoubleBuffered(true);
        container_tabbed.setFont(new java.awt.Font("Lato Black", 0, 16)); // NOI18N
        container_tabbed.setName(""); // NOI18N
        container_tabbed.setPreferredSize(new java.awt.Dimension(980, 465));
        container_tabbed.addMouseListener(new java.awt.event.MouseAdapter() {
            public void mouseClicked(java.awt.event.MouseEvent evt) {
                container_tabbedMouseClicked(evt);
            }
        });

        jPanelDashboard.setBackground(new java.awt.Color(255, 255, 255));

        jLabel1.setFont(new java.awt.Font("Segoe UI Black", 1, 24)); // NOI18N
        jLabel1.setForeground(new java.awt.Color(0, 204, 204));
        jLabel1.setText("DASHBOARD");

        jLabel6.setFont(new java.awt.Font("Lato", 0, 14)); // NOI18N
        jLabel6.setText("Welcome dear");

        jLabel7.setFont(new java.awt.Font("Lato Black", 1, 14)); // NOI18N
        jLabel7.setText("User name");

        javax.swing.GroupLayout jPanelDashboardLayout = new javax.swing.GroupLayout(jPanelDashboard);
        jPanelDashboard.setLayout(jPanelDashboardLayout);
        jPanelDashboardLayout.setHorizontalGroup(
            jPanelDashboardLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, jPanelDashboardLayout.createSequentialGroup()
                .addContainerGap(343, Short.MAX_VALUE)
                .addComponent(jLabel1)
                .addGap(362, 362, 362))
            .addGroup(jPanelDashboardLayout.createSequentialGroup()
                .addGap(233, 233, 233)
                .addComponent(jLabel6, javax.swing.GroupLayout.PREFERRED_SIZE, 115, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addGap(18, 18, 18)
                .addComponent(jLabel7, javax.swing.GroupLayout.PREFERRED_SIZE, 156, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addContainerGap(javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
        );
        jPanelDashboardLayout.setVerticalGroup(
            jPanelDashboardLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanelDashboardLayout.createSequentialGroup()
                .addComponent(jLabel1)
                .addGap(122, 122, 122)
                .addGroup(jPanelDashboardLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(jLabel7, javax.swing.GroupLayout.PREFERRED_SIZE, 28, javax.swing.GroupLayout.PREFERRED_SIZE)
                    .addComponent(jLabel6))
                .addGap(0, 292, Short.MAX_VALUE))
        );

        container_tabbed.addTab("Dashboard", jPanelDashboard);

        jPanelBook.setBackground(new java.awt.Color(255, 255, 255));

        bookTable.setAutoCreateRowSorter(true);
        bookTable.setBackground(new java.awt.Color(204, 255, 255));
        bookTable.setFont(new java.awt.Font("Lato", 0, 14)); // NOI18N
        bookTable.setModel(new javax.swing.table.DefaultTableModel(
            new Object [][] {
                {null, null, null, null, null, null, null},
                {null, null, null, null, null, null, null},
                {null, null, null, null, null, null, null},
                {null, null, null, null, null, null, null},
                {null, null, null, null, null, null, null}
            },
            new String [] {
                "ID", "Title", "Author", "Publication year", "ISBN", "Status", "Description"
            }
        ) {
            Class[] types = new Class [] {
                java.lang.Integer.class, java.lang.String.class, java.lang.String.class, java.lang.Integer.class, java.lang.Integer.class, java.lang.String.class, java.lang.String.class
            };
            boolean[] canEdit = new boolean [] {
                false, false, false, false, false, false, false
            };

            public Class getColumnClass(int columnIndex) {
                return types [columnIndex];
            }

            public boolean isCellEditable(int rowIndex, int columnIndex) {
                return canEdit [columnIndex];
            }
        });
        bookTable.setAutoscrolls(false);
        bookTable.setDebugGraphicsOptions(javax.swing.DebugGraphics.NONE_OPTION);
        bookTable.setShowGrid(true);
        bookTable.getTableHeader().setReorderingAllowed(false);
        jScrollPane1.setViewportView(bookTable);
        bookTable.getAccessibleContext().setAccessibleName("");

        addBookButton.setBackground(new java.awt.Color(0, 204, 51));
        addBookButton.setFont(new java.awt.Font("Segoe UI", 1, 14)); // NOI18N
        addBookButton.setForeground(new java.awt.Color(255, 255, 255));
        addBookButton.setText("Add book");

        jLabel2.setFont(new java.awt.Font("Segoe UI Black", 1, 24)); // NOI18N
        jLabel2.setText("Book list");

        searchField.setFont(new java.awt.Font("Segoe UI", 0, 14)); // NOI18N
        searchField.setToolTipText("Faire une recherche");

        jLabel3.setText("Search a book");

        javax.swing.GroupLayout jPanel1Layout = new javax.swing.GroupLayout(jPanel1);
        jPanel1.setLayout(jPanel1Layout);
        jPanel1Layout.setHorizontalGroup(
            jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel1Layout.createSequentialGroup()
                .addContainerGap()
                .addGroup(jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addComponent(searchField, javax.swing.GroupLayout.PREFERRED_SIZE, 630, javax.swing.GroupLayout.PREFERRED_SIZE)
                    .addComponent(jLabel3))
                .addContainerGap(javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
        );
        jPanel1Layout.setVerticalGroup(
            jPanel1Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, jPanel1Layout.createSequentialGroup()
                .addContainerGap(javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                .addComponent(jLabel3)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(searchField, javax.swing.GroupLayout.PREFERRED_SIZE, 38, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addContainerGap())
        );

        searchField.getAccessibleContext().setAccessibleName("Search");

        javax.swing.GroupLayout jPanelBookLayout = new javax.swing.GroupLayout(jPanelBook);
        jPanelBook.setLayout(jPanelBookLayout);
        jPanelBookLayout.setHorizontalGroup(
            jPanelBookLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanelBookLayout.createSequentialGroup()
                .addGap(19, 19, 19)
                .addGroup(jPanelBookLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, jPanelBookLayout.createSequentialGroup()
                        .addGap(0, 0, Short.MAX_VALUE)
                        .addComponent(jLabel2)
                        .addGap(373, 373, 373))
                    .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, jPanelBookLayout.createSequentialGroup()
                        .addComponent(jScrollPane1, javax.swing.GroupLayout.DEFAULT_SIZE, 833, Short.MAX_VALUE)
                        .addGap(17, 17, 17))
                    .addGroup(jPanelBookLayout.createSequentialGroup()
                        .addComponent(jPanel1, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                        .addGap(29, 29, 29)
                        .addComponent(addBookButton, javax.swing.GroupLayout.PREFERRED_SIZE, 156, javax.swing.GroupLayout.PREFERRED_SIZE)
                        .addContainerGap(javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))))
        );
        jPanelBookLayout.setVerticalGroup(
            jPanelBookLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, jPanelBookLayout.createSequentialGroup()
                .addContainerGap()
                .addComponent(jLabel2)
                .addGroup(jPanelBookLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addGroup(jPanelBookLayout.createSequentialGroup()
                        .addGap(18, 18, 18)
                        .addComponent(jPanel1, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE))
                    .addGroup(jPanelBookLayout.createSequentialGroup()
                        .addGap(31, 31, 31)
                        .addComponent(addBookButton, javax.swing.GroupLayout.PREFERRED_SIZE, 44, javax.swing.GroupLayout.PREFERRED_SIZE)))
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(jScrollPane1, javax.swing.GroupLayout.DEFAULT_SIZE, 334, Short.MAX_VALUE)
                .addContainerGap())
        );

        container_tabbed.addTab("Book", jPanelBook);

        jPanel2.setBackground(new java.awt.Color(255, 255, 255));

        TableLoan.setModel(new javax.swing.table.DefaultTableModel(
            new Object [][] {
                {null, null, null, null, null},
                {null, null, null, null, null},
                {null, null, null, null, null},
                {null, null, null, null, null}
            },
            new String [] {
                "ID", "Book title", "Borrowed Date	", "Due Date	", "Status"
            }
        ) {
            boolean[] canEdit = new boolean [] {
                false, false, false, false, false
            };

            public boolean isCellEditable(int rowIndex, int columnIndex) {
                return canEdit [columnIndex];
            }
        });
        TableLoan.getTableHeader().setReorderingAllowed(false);
        jScrollPane2.setViewportView(TableLoan);
        if (TableLoan.getColumnModel().getColumnCount() > 0) {
            TableLoan.getColumnModel().getColumn(0).setPreferredWidth(2);
        }

        jLabel5.setFont(new java.awt.Font("Segoe UI Black", 1, 18)); // NOI18N
        jLabel5.setHorizontalAlignment(javax.swing.SwingConstants.CENTER);
        jLabel5.setText("Your Loans List");

        returnBookButton.setBackground(new java.awt.Color(255, 153, 51));
        returnBookButton.setFont(new java.awt.Font("Segoe UI", 1, 14)); // NOI18N
        returnBookButton.setForeground(new java.awt.Color(255, 255, 255));
        returnBookButton.setText("Return book");
        returnBookButton.setToolTipText("Select in the table the book to return");
        returnBookButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                returnBookButtonActionPerformed(evt);
            }
        });

        javax.swing.GroupLayout jPanel2Layout = new javax.swing.GroupLayout(jPanel2);
        jPanel2.setLayout(jPanel2Layout);
        jPanel2Layout.setHorizontalGroup(
            jPanel2Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel2Layout.createSequentialGroup()
                .addGap(25, 25, 25)
                .addGroup(jPanel2Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.TRAILING)
                    .addComponent(returnBookButton, javax.swing.GroupLayout.PREFERRED_SIZE, 155, javax.swing.GroupLayout.PREFERRED_SIZE)
                    .addComponent(jScrollPane2, javax.swing.GroupLayout.DEFAULT_SIZE, 829, Short.MAX_VALUE))
                .addGap(15, 15, 15))
            .addGroup(jPanel2Layout.createSequentialGroup()
                .addGap(345, 345, 345)
                .addComponent(jLabel5, javax.swing.GroupLayout.PREFERRED_SIZE, 159, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addContainerGap(javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
        );
        jPanel2Layout.setVerticalGroup(
            jPanel2Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel2Layout.createSequentialGroup()
                .addContainerGap()
                .addComponent(jLabel5)
                .addGap(18, 18, 18)
                .addComponent(jScrollPane2, javax.swing.GroupLayout.DEFAULT_SIZE, 332, Short.MAX_VALUE)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                .addComponent(returnBookButton, javax.swing.GroupLayout.PREFERRED_SIZE, 40, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addGap(31, 31, 31))
        );

        returnBookButton.getAccessibleContext().setAccessibleDescription("");

        container_tabbed.addTab("My loans", jPanel2);

        javax.swing.GroupLayout jPanel3Layout = new javax.swing.GroupLayout(jPanel3);
        jPanel3.setLayout(jPanel3Layout);
        jPanel3Layout.setHorizontalGroup(
            jPanel3Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGap(0, 869, Short.MAX_VALUE)
        );
        jPanel3Layout.setVerticalGroup(
            jPanel3Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGap(0, 475, Short.MAX_VALUE)
        );

        container_tabbed.addTab("Members", jPanel3);

        jPanel_logout.setBackground(new java.awt.Color(255, 255, 255));

        jLabel4.setFont(new java.awt.Font("Lato Black", 1, 18)); // NOI18N
        jLabel4.setHorizontalAlignment(javax.swing.SwingConstants.CENTER);
        jLabel4.setText("Are you sure?");

        jButton1.setBackground(new java.awt.Color(204, 0, 0));
        jButton1.setFont(new java.awt.Font("Segoe UI Black", 1, 18)); // NOI18N
        jButton1.setForeground(new java.awt.Color(255, 255, 255));
        jButton1.setText("Logout");
        jButton1.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                jButton1ActionPerformed(evt);
            }
        });

        javax.swing.GroupLayout jPanel_logoutLayout = new javax.swing.GroupLayout(jPanel_logout);
        jPanel_logout.setLayout(jPanel_logoutLayout);
        jPanel_logoutLayout.setHorizontalGroup(
            jPanel_logoutLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, jPanel_logoutLayout.createSequentialGroup()
                .addContainerGap(338, Short.MAX_VALUE)
                .addGroup(jPanel_logoutLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.TRAILING)
                    .addComponent(jButton1, javax.swing.GroupLayout.PREFERRED_SIZE, 199, javax.swing.GroupLayout.PREFERRED_SIZE)
                    .addComponent(jLabel4, javax.swing.GroupLayout.PREFERRED_SIZE, 179, javax.swing.GroupLayout.PREFERRED_SIZE))
                .addGap(332, 332, 332))
        );
        jPanel_logoutLayout.setVerticalGroup(
            jPanel_logoutLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel_logoutLayout.createSequentialGroup()
                .addGap(83, 83, 83)
                .addComponent(jLabel4)
                .addGap(94, 94, 94)
                .addComponent(jButton1, javax.swing.GroupLayout.PREFERRED_SIZE, 69, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addContainerGap(207, Short.MAX_VALUE))
        );

        container_tabbed.addTab("Logout", jPanel_logout);

        container_tabbed.setSelectedIndex(1);

        javax.swing.GroupLayout layout = new javax.swing.GroupLayout(getContentPane());
        getContentPane().setLayout(layout);
        layout.setHorizontalGroup(
            layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(container_tabbed, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
        );
        layout.setVerticalGroup(
            layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(container_tabbed, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
        );

        pack();
    }// </editor-fold>//GEN-END:initComponents

    private void jButton1ActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_jButton1ActionPerformed
        // TODO add your handling code here:
        UserSession.clearToken();
        dispose();
        launchLoginPage();
    }//GEN-LAST:event_jButton1ActionPerformed

    private void returnBookButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_returnBookButtonActionPerformed
        // TODO add your handling code here:
        int selectedRow = TableLoan.getSelectedRow();
        if (selectedRow != -1) {
            DefaultTableModel model = (DefaultTableModel) TableLoan.getModel();
            int bookId = (int) model.getValueAt(selectedRow, 0);
            returnBook(bookId);
        } else {
            JOptionPane.showMessageDialog(this, "Please select a loan to return", "Warning", JOptionPane.WARNING_MESSAGE);
        }
    }//GEN-LAST:event_returnBookButtonActionPerformed

    private void container_tabbedMouseClicked(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_container_tabbedMouseClicked
        // TODO add your handling code here:
        //loadBooks();
        //loadLoans();
    }//GEN-LAST:event_container_tabbedMouseClicked

    /**
     * @param args the command line arguments
     */

    public void refreshTable() {
        loadBooks();
    }

    private void loadBooks() {
        try {
            String response = callApiWithToken("http://localhost:8000/api/books");
            List<Book> books = parseBooks(response);
            DefaultTableModel model = (DefaultTableModel) bookTable.getModel();
            model.setRowCount(0); // Clear existing rows
            for (Book book : books) {
                model.addRow(new Object[]{
                    book.getId(),
                    book.getTitle(), 
                    book.getAuthor(), 
                    book.getPublicationYear(), 
                    book.getIsbn(), 
                    book.getStatus(), 
                    book.getDescription(),
                });
                    
                    
            }
        } catch (Exception e) {
            JOptionPane.showMessageDialog(this, "Failed to load books", "Error", JOptionPane.ERROR_MESSAGE);
        }
    }

    private String callApiWithToken(String apiUrl) throws Exception {
        URL url = new URL(apiUrl);
        HttpURLConnection conn = (HttpURLConnection) url.openConnection();
        conn.setRequestMethod("GET");

        // Ajouter le token dans les en-têtes de la requête
        String token = UserSession.getToken();
        conn.setRequestProperty("Authorization", "Bearer " + token);

        int responseCode = conn.getResponseCode();
        if (responseCode == HttpURLConnection.HTTP_OK) {
            StringBuilder response;
            try (BufferedReader in = new BufferedReader(new InputStreamReader(conn.getInputStream()))) {
                String inputLine;
                response = new StringBuilder();
                while ((inputLine = in.readLine()) != null) {
                    response.append(inputLine);
                }
            }

            return response.toString();
        } else {
            throw new Exception("Échec de l'appel à l'API, code de réponse: " + responseCode);
        }
    }
  
    private List<Book> parseBooks(String response) {
        List<Book> books = new ArrayList<>();
        JSONArray jsonArray = new JSONArray(response);

        for (int i = 0; i < jsonArray.length(); i++) {
            JSONObject jsonObject = jsonArray.getJSONObject(i);
            Book book = new Book();
            book.setId(jsonObject.getInt("id"));
            book.setTitle(jsonObject.getString("title"));
            book.setAuthor(jsonObject.getString("author"));
            book.setPublicationYear(jsonObject.getInt("published_year"));
            book.setIsbn(jsonObject.getString("isbn"));
            book.setDescription(jsonObject.getString("description"));
            book.setStatus(jsonObject.getString("status"));
            books.add(book);
        }

        return books;
    }

    private void loadLoans() {
        try {
            String response = callApiWithToken("http://localhost:8000/api/user/loans");
            List<Loan> loans = parseLoans(response);
            DefaultTableModel model = (DefaultTableModel) TableLoan.getModel();
            model.setRowCount(0); // Clear existing rows
            for (Loan loan : loans) {
                model.addRow(new Object[]{loan.getBookId(), loan.getBookTitle(), loan.getBorrowedDate(), loan.getDueDate(), loan.getStatus()});
            }
        } catch (Exception e) {
            JOptionPane.showMessageDialog(this, e + "Failed to load loans", "Error", JOptionPane.ERROR_MESSAGE);
        }
    }
    
    private List<Loan> parseLoans(String response) {
        List<Loan> loans = new ArrayList<>();
        JSONArray jsonArray = new JSONArray(response);
    
        for (int i = 0; i < jsonArray.length(); i++) {
            JSONObject jsonObject = jsonArray.getJSONObject(i);
            Loan loan = new Loan();
            loan.setBookId(jsonObject.getJSONObject("book").getInt("id"));
            loan.setBookTitle(jsonObject.getJSONObject("book").getString("title"));
            loan.setBorrowedDate(jsonObject.getString("borrowed_at"));
            loan.setDueDate(jsonObject.getString("due_date"));
            loan.setStatus(jsonObject.getString("status"));
            loans.add(loan);
        }
    
        return loans;
    }

    public static void main(String args[]) {
        /* Set the Nimbus look and feel */
        //<editor-fold defaultstate="collapsed" desc=" Look and feel setting code (optional) ">
        /* If Nimbus (introduced in Java SE 6) is not available, stay with the default look and feel.
         * For details see http://download.oracle.com/javase/tutorial/uiswing/lookandfeel/plaf.html 
         */
        try {
            for (javax.swing.UIManager.LookAndFeelInfo info : javax.swing.UIManager.getInstalledLookAndFeels()) {
                if ("Nimbus".equals(info.getName())) {
                    javax.swing.UIManager.setLookAndFeel(info.getClassName());
                    break;
                }
            }
        } catch (ClassNotFoundException ex) {
            java.util.logging.Logger.getLogger(dashboard.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        } catch (InstantiationException ex) {
            java.util.logging.Logger.getLogger(dashboard.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        } catch (IllegalAccessException ex) {
            java.util.logging.Logger.getLogger(dashboard.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        } catch (javax.swing.UnsupportedLookAndFeelException ex) {
            java.util.logging.Logger.getLogger(dashboard.class.getName()).log(java.util.logging.Level.SEVERE, null, ex);
        }
        //</editor-fold>

        /* Create and display the form */
        java.awt.EventQueue.invokeLater(() -> {
            new dashboard().setVisible(true);
        });
        
    }

    // Variables declaration - do not modify//GEN-BEGIN:variables
    private javax.swing.JTable TableLoan;
    private javax.swing.JButton addBookButton;
    private javax.swing.JTable bookTable;
    private javax.swing.JTabbedPane container_tabbed;
    private javax.swing.JButton jButton1;
    private javax.swing.JLabel jLabel1;
    private javax.swing.JLabel jLabel2;
    private javax.swing.JLabel jLabel3;
    private javax.swing.JLabel jLabel4;
    private javax.swing.JLabel jLabel5;
    private javax.swing.JLabel jLabel6;
    private javax.swing.JLabel jLabel7;
    private javax.swing.JPanel jPanel1;
    private javax.swing.JPanel jPanel2;
    private javax.swing.JPanel jPanel3;
    private javax.swing.JPanel jPanelBook;
    private javax.swing.JPanel jPanelDashboard;
    private javax.swing.JPanel jPanel_logout;
    private javax.swing.JScrollPane jScrollPane1;
    private javax.swing.JScrollPane jScrollPane2;
    private javax.swing.JButton returnBookButton;
    private javax.swing.JTextField searchField;
    // End of variables declaration//GEN-END:variables
}
