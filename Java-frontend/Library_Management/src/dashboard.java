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
    private List<User> nonMembers; // Variable pour stocker les utilisateurs non membres

    public dashboard() {
        initComponents();
        rowSorter = new TableRowSorter<>((DefaultTableModel) bookTable.getModel());
        bookTable.setRowSorter(rowSorter);
        
        loadBooks();
        loadLoans();
        loadMembers();
        loadNonMembers();
                
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
                        String image = (String) model.getValueAt(selectedRow, 7);
                        
                        Book book = new Book();
                        book.setId(id);
                        book.setTitle(title);
                        book.setAuthor(author);
                        book.setPublicationYear(publicationYear);
                        book.setIsbn(isbn);
                        book.setStatus(status);
                        book.setImagePath("http://localhost:8000/" + image); // Set the image path based on ISBN or another identifier
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
                loadBooks();// Refresh book after return
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

    private void callApiToDeleteMember(int memberId) throws Exception {
    String apiUrl = "http://localhost:8000/api/members/" + memberId;
    callApiWithTokenDELETE(apiUrl);
    }

    private void callApiWithTokenDELETE(String apiUrl) throws Exception {
        URL url = new URL(apiUrl);
        HttpURLConnection conn = (HttpURLConnection) url.openConnection();
        conn.setRequestMethod("DELETE");
        conn.setRequestProperty("Content-Type", "application/json");

        // Add token in request headers
        String token = UserSession.getToken();
        conn.setRequestProperty("Authorization", "Bearer " + token);

        int responseCode = conn.getResponseCode();
        if (responseCode != HttpURLConnection.HTTP_OK) {
            throw new Exception("API call failed, response code: " + responseCode);
        }
    }
    @SuppressWarnings("unchecked")
    // <editor-fold defaultstate="collapsed" desc="Generated Code">//GEN-BEGIN:initComponents
    private void initComponents() {

        container_tabbed = new javax.swing.JTabbedPane();
        jPanelDashboard = new javax.swing.JPanel();
        jLabel1 = new javax.swing.JLabel();
        jLabel6 = new javax.swing.JLabel();
        jLabel7 = new javax.swing.JLabel();
        refreshbtn0 = new javax.swing.JLabel();
        jPanelBook = new javax.swing.JPanel();
        jScrollPane1 = new javax.swing.JScrollPane();
        bookTable = new javax.swing.JTable();
        addBookButton = new javax.swing.JButton();
        jLabel2 = new javax.swing.JLabel();
        jPanel1 = new javax.swing.JPanel();
        searchField = new javax.swing.JTextField();
        jLabel3 = new javax.swing.JLabel();
        refreshbtn1 = new javax.swing.JLabel();
        jPanel2 = new javax.swing.JPanel();
        jScrollPane2 = new javax.swing.JScrollPane();
        TableLoan = new javax.swing.JTable();
        jLabel5 = new javax.swing.JLabel();
        returnBookButton = new javax.swing.JButton();
        refreshbtn2 = new javax.swing.JLabel();
        jPanel3 = new javax.swing.JPanel();
        jPanel4 = new javax.swing.JPanel();
        addMemberButton = new javax.swing.JButton();
        jScrollPane3 = new javax.swing.JScrollPane();
        memberTable = new javax.swing.JTable();
        jLabel8 = new javax.swing.JLabel();
        jPanel5 = new javax.swing.JPanel();
        searchMember = new javax.swing.JTextField();
        jLabel9 = new javax.swing.JLabel();
        delMemberButton = new javax.swing.JButton();
        refreshbtn3 = new javax.swing.JLabel();
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

        refreshbtn0.setFont(new java.awt.Font("Segoe UI Black", 1, 14)); // NOI18N
        refreshbtn0.setForeground(new java.awt.Color(0, 153, 255));
        refreshbtn0.setText("Refresh page");
        refreshbtn0.addMouseListener(new java.awt.event.MouseAdapter() {
            public void mouseClicked(java.awt.event.MouseEvent evt) {
                refreshbtn0MouseClicked(evt);
            }
        });

        javax.swing.GroupLayout jPanelDashboardLayout = new javax.swing.GroupLayout(jPanelDashboard);
        jPanelDashboard.setLayout(jPanelDashboardLayout);
        jPanelDashboardLayout.setHorizontalGroup(
            jPanelDashboardLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, jPanelDashboardLayout.createSequentialGroup()
                .addContainerGap(343, Short.MAX_VALUE)
                .addComponent(jLabel1)
                .addGap(250, 250, 250)
                .addComponent(refreshbtn0, javax.swing.GroupLayout.PREFERRED_SIZE, 106, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addContainerGap())
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
                .addGroup(jPanelDashboardLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addComponent(jLabel1)
                    .addGroup(jPanelDashboardLayout.createSequentialGroup()
                        .addContainerGap()
                        .addComponent(refreshbtn0, javax.swing.GroupLayout.PREFERRED_SIZE, 41, javax.swing.GroupLayout.PREFERRED_SIZE)))
                .addGap(108, 108, 108)
                .addGroup(jPanelDashboardLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(jLabel7, javax.swing.GroupLayout.PREFERRED_SIZE, 28, javax.swing.GroupLayout.PREFERRED_SIZE)
                    .addComponent(jLabel6))
                .addGap(0, 282, Short.MAX_VALUE))
        );

        container_tabbed.addTab("Dashboard", jPanelDashboard);

        jPanelBook.setBackground(new java.awt.Color(255, 255, 255));

        bookTable.setAutoCreateRowSorter(true);
        bookTable.setBackground(new java.awt.Color(204, 255, 255));
        bookTable.setFont(new java.awt.Font("Lato", 0, 14)); // NOI18N
        bookTable.setModel(new javax.swing.table.DefaultTableModel(
            new Object [][] {
                {null, null, null, null, null, null, null, null},
                {null, null, null, null, null, null, null, null},
                {null, null, null, null, null, null, null, null},
                {null, null, null, null, null, null, null, null},
                {null, null, null, null, null, null, null, null}
            },
            new String [] {
                "ID", "Title", "Author", "Publication year", "ISBN", "Status", "Description", "image"
            }
        ) {
            Class[] types = new Class [] {
                java.lang.Integer.class, java.lang.String.class, java.lang.String.class, java.lang.Integer.class, java.lang.Integer.class, java.lang.String.class, java.lang.String.class, java.lang.Object.class
            };
            boolean[] canEdit = new boolean [] {
                false, false, false, false, false, false, false, true
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

        refreshbtn1.setFont(new java.awt.Font("Segoe UI Black", 1, 14)); // NOI18N
        refreshbtn1.setForeground(new java.awt.Color(0, 153, 255));
        refreshbtn1.setText("Refresh page");
        refreshbtn1.addMouseListener(new java.awt.event.MouseAdapter() {
            public void mouseClicked(java.awt.event.MouseEvent evt) {
                refreshbtn1MouseClicked(evt);
            }
        });

        javax.swing.GroupLayout jPanelBookLayout = new javax.swing.GroupLayout(jPanelBook);
        jPanelBook.setLayout(jPanelBookLayout);
        jPanelBookLayout.setHorizontalGroup(
            jPanelBookLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanelBookLayout.createSequentialGroup()
                .addGap(19, 19, 19)
                .addGroup(jPanelBookLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, jPanelBookLayout.createSequentialGroup()
                        .addComponent(jScrollPane1, javax.swing.GroupLayout.DEFAULT_SIZE, 833, Short.MAX_VALUE)
                        .addGap(17, 17, 17))
                    .addGroup(jPanelBookLayout.createSequentialGroup()
                        .addComponent(jPanel1, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                        .addGap(29, 29, 29)
                        .addComponent(addBookButton, javax.swing.GroupLayout.PREFERRED_SIZE, 156, javax.swing.GroupLayout.PREFERRED_SIZE)
                        .addContainerGap(javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))))
            .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, jPanelBookLayout.createSequentialGroup()
                .addContainerGap(javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                .addComponent(jLabel2)
                .addGap(261, 261, 261)
                .addComponent(refreshbtn1, javax.swing.GroupLayout.PREFERRED_SIZE, 106, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addContainerGap())
        );
        jPanelBookLayout.setVerticalGroup(
            jPanelBookLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, jPanelBookLayout.createSequentialGroup()
                .addContainerGap()
                .addGroup(jPanelBookLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.BASELINE)
                    .addComponent(jLabel2)
                    .addComponent(refreshbtn1, javax.swing.GroupLayout.PREFERRED_SIZE, 41, javax.swing.GroupLayout.PREFERRED_SIZE))
                .addGap(8, 8, 8)
                .addGroup(jPanelBookLayout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addComponent(jPanel1, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                    .addGroup(jPanelBookLayout.createSequentialGroup()
                        .addGap(23, 23, 23)
                        .addComponent(addBookButton, javax.swing.GroupLayout.PREFERRED_SIZE, 44, javax.swing.GroupLayout.PREFERRED_SIZE)))
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(jScrollPane1, javax.swing.GroupLayout.DEFAULT_SIZE, 326, Short.MAX_VALUE)
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

        refreshbtn2.setFont(new java.awt.Font("Segoe UI Black", 1, 14)); // NOI18N
        refreshbtn2.setForeground(new java.awt.Color(0, 153, 255));
        refreshbtn2.setText("Refresh page");
        refreshbtn2.addMouseListener(new java.awt.event.MouseAdapter() {
            public void mouseClicked(java.awt.event.MouseEvent evt) {
                refreshbtn2MouseClicked(evt);
            }
        });

        javax.swing.GroupLayout jPanel2Layout = new javax.swing.GroupLayout(jPanel2);
        jPanel2.setLayout(jPanel2Layout);
        jPanel2Layout.setHorizontalGroup(
            jPanel2Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, jPanel2Layout.createSequentialGroup()
                .addGap(345, 345, 345)
                .addComponent(jLabel5, javax.swing.GroupLayout.PREFERRED_SIZE, 159, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED, 244, Short.MAX_VALUE)
                .addComponent(refreshbtn2, javax.swing.GroupLayout.PREFERRED_SIZE, 106, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addGap(15, 15, 15))
            .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, jPanel2Layout.createSequentialGroup()
                .addContainerGap()
                .addComponent(jScrollPane2)
                .addContainerGap())
            .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, jPanel2Layout.createSequentialGroup()
                .addContainerGap(javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                .addComponent(returnBookButton, javax.swing.GroupLayout.PREFERRED_SIZE, 155, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addContainerGap())
        );
        jPanel2Layout.setVerticalGroup(
            jPanel2Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel2Layout.createSequentialGroup()
                .addContainerGap()
                .addGroup(jPanel2Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addComponent(jLabel5)
                    .addComponent(refreshbtn2, javax.swing.GroupLayout.PREFERRED_SIZE, 41, javax.swing.GroupLayout.PREFERRED_SIZE))
                .addGap(34, 34, 34)
                .addComponent(jScrollPane2, javax.swing.GroupLayout.DEFAULT_SIZE, 332, Short.MAX_VALUE)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(returnBookButton, javax.swing.GroupLayout.PREFERRED_SIZE, 40, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addContainerGap())
        );

        returnBookButton.getAccessibleContext().setAccessibleDescription("");

        container_tabbed.addTab("My loans", jPanel2);

        jPanel4.setBackground(new java.awt.Color(255, 255, 255));

        addMemberButton.setBackground(new java.awt.Color(0, 204, 102));
        addMemberButton.setFont(new java.awt.Font("Segoe UI", 1, 14)); // NOI18N
        addMemberButton.setForeground(new java.awt.Color(255, 255, 255));
        addMemberButton.setText("Add new member");
        addMemberButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                addMemberButtonActionPerformed(evt);
            }
        });

        memberTable.setModel(new javax.swing.table.DefaultTableModel(
            new Object [][] {
                {null, null, null, null, null, null, null, null},
                {null, null, null, null, null, null, null, null},
                {null, null, null, null, null, null, null, null},
                {null, null, null, null, null, null, null, null},
                {null, null, null, null, null, null, null, null}
            },
            new String [] {
                "ID", "Lastname", "Phone", "Address", "Email", "Join date", "Expiry date", "Status"
            }
        ) {
            boolean[] canEdit = new boolean [] {
                false, false, false, false, false, false, false, false
            };

            public boolean isCellEditable(int rowIndex, int columnIndex) {
                return canEdit [columnIndex];
            }
        });
        memberTable.setColumnSelectionAllowed(true);
        memberTable.setRowSelectionAllowed(true);
        memberTable.setShowGrid(true);
        memberTable.setShowHorizontalLines(false);
        memberTable.setShowVerticalLines(false);
        memberTable.setSurrendersFocusOnKeystroke(true);
        memberTable.getTableHeader().setReorderingAllowed(false);
        memberTable.setVerifyInputWhenFocusTarget(false);
        jScrollPane3.setViewportView(memberTable);
        memberTable.getColumnModel().getSelectionModel().setSelectionMode(javax.swing.ListSelectionModel.SINGLE_SELECTION);

        jLabel8.setFont(new java.awt.Font("Segoe UI Black", 1, 24)); // NOI18N
        jLabel8.setHorizontalAlignment(javax.swing.SwingConstants.CENTER);
        jLabel8.setText("Members list");

        searchMember.setFont(new java.awt.Font("Segoe UI", 0, 14)); // NOI18N
        searchMember.setToolTipText("Faire une recherche");

        jLabel9.setText("Search a member");

        javax.swing.GroupLayout jPanel5Layout = new javax.swing.GroupLayout(jPanel5);
        jPanel5.setLayout(jPanel5Layout);
        jPanel5Layout.setHorizontalGroup(
            jPanel5Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel5Layout.createSequentialGroup()
                .addContainerGap()
                .addGroup(jPanel5Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addComponent(searchMember, javax.swing.GroupLayout.PREFERRED_SIZE, 630, javax.swing.GroupLayout.PREFERRED_SIZE)
                    .addComponent(jLabel9))
                .addContainerGap(javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))
        );
        jPanel5Layout.setVerticalGroup(
            jPanel5Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(javax.swing.GroupLayout.Alignment.TRAILING, jPanel5Layout.createSequentialGroup()
                .addContainerGap(javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                .addComponent(jLabel9)
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                .addComponent(searchMember, javax.swing.GroupLayout.PREFERRED_SIZE, 38, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addContainerGap())
        );

        delMemberButton.setBackground(new java.awt.Color(204, 51, 0));
        delMemberButton.setFont(new java.awt.Font("Segoe UI", 1, 14)); // NOI18N
        delMemberButton.setForeground(new java.awt.Color(255, 255, 255));
        delMemberButton.setText("Delete member");
        delMemberButton.addActionListener(new java.awt.event.ActionListener() {
            public void actionPerformed(java.awt.event.ActionEvent evt) {
                delMemberButtonActionPerformed(evt);
            }
        });

        refreshbtn3.setFont(new java.awt.Font("Segoe UI Black", 1, 14)); // NOI18N
        refreshbtn3.setForeground(new java.awt.Color(0, 153, 255));
        refreshbtn3.setText("Refresh page");
        refreshbtn3.addMouseListener(new java.awt.event.MouseAdapter() {
            public void mouseClicked(java.awt.event.MouseEvent evt) {
                refreshbtn3MouseClicked(evt);
            }
        });

        javax.swing.GroupLayout jPanel4Layout = new javax.swing.GroupLayout(jPanel4);
        jPanel4.setLayout(jPanel4Layout);
        jPanel4Layout.setHorizontalGroup(
            jPanel4Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel4Layout.createSequentialGroup()
                .addGroup(jPanel4Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addGroup(jPanel4Layout.createSequentialGroup()
                        .addGap(351, 351, 351)
                        .addComponent(jLabel8)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
                        .addComponent(refreshbtn3, javax.swing.GroupLayout.PREFERRED_SIZE, 106, javax.swing.GroupLayout.PREFERRED_SIZE))
                    .addGroup(jPanel4Layout.createSequentialGroup()
                        .addContainerGap()
                        .addComponent(jScrollPane3)))
                .addContainerGap())
            .addGroup(jPanel4Layout.createSequentialGroup()
                .addGap(14, 14, 14)
                .addComponent(jPanel5, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                .addGroup(jPanel4Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addGroup(jPanel4Layout.createSequentialGroup()
                        .addGap(59, 59, 59)
                        .addComponent(delMemberButton, javax.swing.GroupLayout.PREFERRED_SIZE, 142, javax.swing.GroupLayout.PREFERRED_SIZE)
                        .addGap(17, 17, 17))
                    .addGroup(jPanel4Layout.createSequentialGroup()
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                        .addComponent(addMemberButton)
                        .addContainerGap(javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE))))
        );
        jPanel4Layout.setVerticalGroup(
            jPanel4Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addGroup(jPanel4Layout.createSequentialGroup()
                .addGap(7, 7, 7)
                .addGroup(jPanel4Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.TRAILING)
                    .addComponent(jLabel8)
                    .addComponent(refreshbtn3, javax.swing.GroupLayout.PREFERRED_SIZE, 41, javax.swing.GroupLayout.PREFERRED_SIZE))
                .addGap(18, 18, 18)
                .addGroup(jPanel4Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
                    .addComponent(jPanel5, javax.swing.GroupLayout.PREFERRED_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.PREFERRED_SIZE)
                    .addGroup(jPanel4Layout.createSequentialGroup()
                        .addComponent(addMemberButton, javax.swing.GroupLayout.PREFERRED_SIZE, 33, javax.swing.GroupLayout.PREFERRED_SIZE)
                        .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.RELATED)
                        .addComponent(delMemberButton, javax.swing.GroupLayout.PREFERRED_SIZE, 33, javax.swing.GroupLayout.PREFERRED_SIZE)))
                .addPreferredGap(javax.swing.LayoutStyle.ComponentPlacement.UNRELATED)
                .addComponent(jScrollPane3, javax.swing.GroupLayout.DEFAULT_SIZE, 309, Short.MAX_VALUE)
                .addContainerGap())
        );

        javax.swing.GroupLayout jPanel3Layout = new javax.swing.GroupLayout(jPanel3);
        jPanel3.setLayout(jPanel3Layout);
        jPanel3Layout.setHorizontalGroup(
            jPanel3Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(jPanel4, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
        );
        jPanel3Layout.setVerticalGroup(
            jPanel3Layout.createParallelGroup(javax.swing.GroupLayout.Alignment.LEADING)
            .addComponent(jPanel4, javax.swing.GroupLayout.DEFAULT_SIZE, javax.swing.GroupLayout.DEFAULT_SIZE, Short.MAX_VALUE)
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
                .addContainerGap(229, Short.MAX_VALUE))
        );

        container_tabbed.addTab("Logout", jPanel_logout);

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

    private void addMemberButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_addMemberButtonActionPerformed
        // TODO add your handling code here:
        SelectUserDialog dialog = new SelectUserDialog(this, nonMembers);
        dialog.setVisible(true);
        loadMembers();
    }//GEN-LAST:event_addMemberButtonActionPerformed

    private void delMemberButtonActionPerformed(java.awt.event.ActionEvent evt) {//GEN-FIRST:event_delMemberButtonActionPerformed
        // TODO add your handling code here:
        int selectedRow = memberTable.getSelectedRow();
        if (selectedRow != -1) {
            DefaultTableModel model = (DefaultTableModel) memberTable.getModel();
            int memberId = Integer.parseInt(model.getValueAt(selectedRow, 0).toString()); // Convertir en entier

            int confirm = JOptionPane.showConfirmDialog(this, "Are you sure you want to delete this member?", "Confirm Delete", JOptionPane.YES_NO_OPTION);
            if (confirm == JOptionPane.YES_OPTION) {
                try {
                    callApiToDeleteMember(memberId);
                    loadMembers(); // Refresh members list after deletion
                } catch (Exception e) {
                    JOptionPane.showMessageDialog(this, "Failed to delete member", "Error", JOptionPane.ERROR_MESSAGE);
                }
            }
        } else {
            JOptionPane.showMessageDialog(this, "Please select a member to delete", "Warning", JOptionPane.WARNING_MESSAGE);
        }
    }//GEN-LAST:event_delMemberButtonActionPerformed

    private void refreshbtn0MouseClicked(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_refreshbtn0MouseClicked
        refreshTable();
    }//GEN-LAST:event_refreshbtn0MouseClicked

    private void refreshbtn1MouseClicked(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_refreshbtn1MouseClicked
        // TODO add your handling code here:
        loadBooks();
        loadLoans();
    }//GEN-LAST:event_refreshbtn1MouseClicked

    private void refreshbtn2MouseClicked(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_refreshbtn2MouseClicked
        // TODO add your handling code here:
        loadLoans();
        loadBooks();
    }//GEN-LAST:event_refreshbtn2MouseClicked

    private void refreshbtn3MouseClicked(java.awt.event.MouseEvent evt) {//GEN-FIRST:event_refreshbtn3MouseClicked
        // TODO add your handling code here:
        loadMembers();
    }//GEN-LAST:event_refreshbtn3MouseClicked

    public void refreshTable() {
        loadBooks();
        loadLoans();
        loadMembers();
    }
    
     private void loadNonMembers() {
        try {
            String response = callApiWithToken("http://localhost:8000/api/users/non-members");
            nonMembers = parseUsers(response);
        } catch (Exception e) {
            JOptionPane.showMessageDialog(this, "Failed to load non-members", "Error", JOptionPane.ERROR_MESSAGE);
        }
    }

    private List<User> parseUsers(String response) {
        List<User> users = new ArrayList<>();
        JSONArray jsonArray = new JSONArray(response);

        for (int i = 0; i < jsonArray.length(); i++) {
            JSONObject jsonObject = jsonArray.getJSONObject(i);
            User user = new User();
            user.setId(jsonObject.getInt("id"));
            user.setLastname(jsonObject.getString("lastname"));
            user.setFirstname(jsonObject.getString("firstname"));
            user.setEmail(jsonObject.getString("email"));
            users.add(user);
        }

        return users;
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
                    book.getImagePath(),
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
        conn.setRequestProperty("Content-Type", "application/json");

        // Add token in request headers
        String token = UserSession.getToken();
        conn.setRequestProperty("Authorization", "Bearer " + token);

        int responseCode = conn.getResponseCode();
        if (responseCode == HttpURLConnection.HTTP_OK) {
            try (BufferedReader in = new BufferedReader(new InputStreamReader(conn.getInputStream()))) {
                String inputLine;
                StringBuilder content = new StringBuilder();
                while ((inputLine = in.readLine()) != null) {
                    content.append(inputLine);
                }
                return content.toString();
            }
        } else {
            throw new Exception("API call failed, response code: " + responseCode);
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
            book.setImagePath(jsonObject.getString("image"));
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
    
    private void loadMembers() {
        try {
            String response = callApiWithToken("http://localhost:8000/api/members");
            List<Member> members = parseMembers(response);
            DefaultTableModel model = (DefaultTableModel) memberTable.getModel();
            model.setRowCount(0); // Clear existing rows
            for (Member member : members) {
                model.addRow(new Object[]{
                    member.getId(),
                    member.getLastname(),
                    member.getPhone(),
                    member.getAddress(),
                    member.getEmail(),
                    member.getJoin_date(),
                    member.getExpiry_date(),
                    member.getStatus(),
                });
            }
        } catch (Exception e) {
            JOptionPane.showMessageDialog(this, "Failed to load members" + e, "Error", JOptionPane.ERROR_MESSAGE);
            System.err.println(""+e);
        }
    }

    private List<Member> parseMembers(String response) {
        List<Member> members = new ArrayList<>();
        JSONArray jsonArray = new JSONArray(response);

        for (int i = 0; i < jsonArray.length(); i++) {
            JSONObject jsonObject = jsonArray.getJSONObject(i);
            Member member = new Member();
            member.setId(jsonObject.getInt("id"));
            member.setLastname(jsonObject.getJSONObject("user").optString("lastname", "N/R"));
            member.setPhone(jsonObject.optString("phone", "N/R"));
            member.setAddress(jsonObject.optString("address", "N/R"));
            member.setEmail(jsonObject.getJSONObject("user").getString("email"));
            member.setJoin_date(jsonObject.optString("join_date", "N/R"));
            member.setExpiry_date(jsonObject.optString("expiry_date", "N/R"));
            member.setStatus(jsonObject.getString("status"));
            
            JSONObject userObject = jsonObject.getJSONObject("user");
            User user = new User();
            user.setId(userObject.getInt("id"));
            user.setLastname(userObject.getString("lastname"));
            member.setUser(user);
            
            members.add(member);
        }

        return members;
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
    private javax.swing.JButton addMemberButton;
    private javax.swing.JTable bookTable;
    private javax.swing.JTabbedPane container_tabbed;
    private javax.swing.JButton delMemberButton;
    private javax.swing.JButton jButton1;
    private javax.swing.JLabel jLabel1;
    private javax.swing.JLabel jLabel2;
    private javax.swing.JLabel jLabel3;
    private javax.swing.JLabel jLabel4;
    private javax.swing.JLabel jLabel5;
    private javax.swing.JLabel jLabel6;
    private javax.swing.JLabel jLabel7;
    private javax.swing.JLabel jLabel8;
    private javax.swing.JLabel jLabel9;
    private javax.swing.JPanel jPanel1;
    private javax.swing.JPanel jPanel2;
    private javax.swing.JPanel jPanel3;
    private javax.swing.JPanel jPanel4;
    private javax.swing.JPanel jPanel5;
    private javax.swing.JPanel jPanelBook;
    private javax.swing.JPanel jPanelDashboard;
    private javax.swing.JPanel jPanel_logout;
    private javax.swing.JScrollPane jScrollPane1;
    private javax.swing.JScrollPane jScrollPane2;
    private javax.swing.JScrollPane jScrollPane3;
    private javax.swing.JTable memberTable;
    private javax.swing.JLabel refreshbtn0;
    private javax.swing.JLabel refreshbtn1;
    private javax.swing.JLabel refreshbtn2;
    private javax.swing.JLabel refreshbtn3;
    private javax.swing.JButton returnBookButton;
    private javax.swing.JTextField searchField;
    private javax.swing.JTextField searchMember;
    // End of variables declaration//GEN-END:variables
}
