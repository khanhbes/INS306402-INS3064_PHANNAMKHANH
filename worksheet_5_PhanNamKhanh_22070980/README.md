# Session 06 - Database Design

## Part 1: Normalization
Từ bảng dữ liệu thô `STUDENT_GRADES_RAW`, dữ liệu được chuẩn hóa về 3NF để loại bỏ các phụ thuộc một phần và phụ thuộc bắc cầu nhằm tránh dư thừa dữ liệu.

| Table Name | Primary Key | Foreign Key | Normal Form | Description |
|---|---|---|---|---|
| Students | StudentID | None | 3NF | Lưu trữ thông tin cá nhân của sinh viên |
| Professors | ProfessorEmail | None | 3NF | Lưu trữ thông tin của giảng viên |
| Courses | CourseID | ProfessorEmail | 3NF | Lưu trữ thông tin môn học và giảng viên phụ trách |
| Student_Grades | StudentID, CourseID | StudentID, CourseID | 3NF | Bảng trung gian lưu trữ điểm số của sinh viên cho từng môn học |

## Part 2: Relationships
Dưới đây là phân tích các loại mối quan hệ và vị trí đặt Khóa ngoại (Foreign Key):

1. **AUTHOR – BOOK:** * Relationship Type: One-to-Many (1:N) *(Giả định một tác giả viết nhiều sách, mỗi sách do một người viết chính)*.
   * FK Location: Đặt ở bảng **BOOK** (Bên "Nhiều"). *(Lưu ý: Nếu hệ thống cho phép đồng tác giả, đây sẽ là N:N và cần bảng trung gian).*
2. **CITIZEN – PASSPORT:** * Relationship Type: One-to-One (1:1).
   * FK Location: Đặt ở bảng **PASSPORT** (Kèm theo ràng buộc UNIQUE).
3. **CUSTOMER – ORDER:** * Relationship Type: One-to-Many (1:N). 
   * FK Location: Đặt ở bảng **ORDER**.
4. **STUDENT – CLASS:** * Relationship Type: Many-to-Many (N:N). 
   * FK Location: Đặt ở **Bảng trung gian (Junction Table)** (Ví dụ: `Enrollments`), bảng này sẽ chứa FK trỏ về Student và Class.
5. **TEAM – PLAYER:** * Relationship Type: One-to-Many (1:N). 
   * FK Location: Đặt ở bảng **PLAYER**.