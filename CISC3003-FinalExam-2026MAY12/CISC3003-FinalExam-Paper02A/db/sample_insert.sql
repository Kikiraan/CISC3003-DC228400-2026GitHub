USE paper02a_db;

INSERT INTO student_feedback
    (full_name, email, phone, age, program, study_mode, interests, experience)
VALUES
    (
        'Wang Yufeng',
        'dc228400@example.com',
        '+853 6200 2400',
        20,
        'BSc in Computer Science',
        'Hybrid',
        'Academic Advising, Programming Workshop',
        'This sample record demonstrates the manual SQL INSERT INTO statement required in Scenario A.'
    );
