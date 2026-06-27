<!DOCTYPE html>
<html>
<head>
    <style>
        @page { size: a4; margin: 40px; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #333; line-height: 1.4; font-size: 11px; }
        .header { text-align: center; border-bottom: 2px solid #f97316; padding-bottom: 20px; margin-bottom: 20px; }
        .inst-name { font-size: 24px; font-weight: bold; color: #f97316; }
        .doc-title { font-size: 18px; font-weight: bold; margin-top: 5px; }
        
        .student-info { margin-bottom: 20px; }
        .student-info table { width: 100%; }
        .student-info td { padding: 5px; border: none; }

        .section-title { background: #f8fafc; padding: 5px 10px; font-weight: bold; border-left: 4px solid #f97316; margin: 15px 0 10px 0; font-size: 12px; }
        
        .module-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .module-table th, .module-table td { border: 1px solid #e2e8f0; padding: 8px; text-align: left; }
        .module-table th { background: #f1f5f9; }

        .summary-text { text-align: justify; color: #475569; margin-bottom: 20px; }
        
        .footer { margin-top: 40px; border-top: 1px solid #e2e8f0; padding-top: 20px; }
        .signature-grid { width: 100%; margin-top: 30px; }
        .signature-grid td { text-align: center; width: 33%; }
        .sig-line { border-top: 1px solid #333; width: 80%; margin: 30px auto 5px auto; }
    </style>
</head>
<body>
    <div class="header">
        <div class="inst-name">BAPS SWAMINARAYAN SANSTHA</div>
        <div class="doc-title">OFFICIAL IPDC ACADEMIC TRANSCRIPT</div>
        <div>INSTITUTIONAL REPOSITORY OF CHARACTER DEVELOPMENT</div>
    </div>

    <div class="student-info">
        <table>
            <tr>
                <td><strong>Student Name:</strong> {{ $name }}</td>
                <td><strong>Enrollment ID:</strong> BAPS-{{ mt_rand(10000, 99999) }}</td>
            </tr>
            <tr>
                <td><strong>Academic Year:</strong> 2024-2025</td>
                <td><strong>Issue Date:</strong> {{ date('d M Y') }}</td>
            </tr>
        </table>
    </div>

    <div class="section-title">ACADEMIC PERFORMANCE SUMMARY (IPDC CURRICULUM)</div>
    <table class="module-table">
        <thead>
            <tr>
                <th>Module Code</th>
                <th>Module Description</th>
                <th>Credits</th>
                <th>Grade</th>
                <th>Reflection Score</th>
            </tr>
        </thead>
        <tbody>
            <tr><td>IPDC-M101</td><td>Facing Failures: Equanimity in Adversity</td><td>2.0</td><td>A+</td><td>95%</td></tr>
            <tr><td>IPDC-M102</td><td>Family Bonds: Bridging Generational Gaps</td><td>1.5</td><td>A</td><td>88%</td></tr>
            <tr><td>IPDC-M103</td><td>Remaking Yourself: Habit Transformation</td><td>2.0</td><td>A+</td><td>92%</td></tr>
            <tr><td>IPDC-M104</td><td>Learning from Legends: Life of Bhagwan Swaminarayan</td><td>3.0</td><td>O</td><td>98%</td></tr>
            <tr><td>IPDC-M105</td><td>Health & Wellness: Holistic Development</td><td>1.5</td><td>A</td><td>85%</td></tr>
        </tbody>
    </table>

    <div class="section-title">DETAILED INSTITUTIONAL REFLECTION & BEHAVIORAL ANALYTICS</div>
    <div class="summary-text">
        The Integrated Personality Development Course (IPDC) is a comprehensive educational framework designed to cultivate a multi-dimensional personality that harmoniously blends intellectual prowess with deep-rooted moral and spiritual values. This transcript provides an exhaustive record of the student's engagement with the curriculum, which is structured around the core philosophy of "Remaking Yourself." The student has demonstrated a remarkable ability to internalize the teachings of HH Pramukh Swami Maharaj, specifically the principle that "In the joy of others lies our own." Throughout the academic tenure, the student has actively participated in reflective sessions that challenge conventional wisdom and encourage a higher standard of ethical conduct. <br><br>
        
        In the 'Facing Failures' module, the student provided a 1000-word reflective analysis on the life of Sardar Patel, drawing parallels between historical resilience and contemporary challenges. Their submission highlighted a nuanced understanding of internal stability versus external validation. The 'Family Bonds' section revealed the student's growth in communication and emotional intelligence, with a documented project on 'Improving Parent-Child Synergy' that received high commendation from the faculty mentors. This module is critical in an age of digital disconnection, and the student's commitment to strengthening familial ties is a testament to their character. <br><br>
        
        The 'Learning from Legends' module, focusing on the spiritual lineage and the life-work of HH Bhagwan Swaminarayan and HH Pramukh Swami Maharaj, was approached with deep reverence and academic rigor. The student's thesis on 'Servant Leadership in the 21st Century' integrated institutional values with modern management theories, showcasing a capacity for cross-disciplinary synthesis. This level of engagement indicates that the student is not merely a passive recipient of information but an active practitioner of value-based leadership. <br><br>

        Furthermore, the student's performance in the 'Health and Wellness' module demonstrated a commitment to holistic living, incorporating mindfulness, yoga, and a balanced lifestyle as essential pillars of personality development. The reflections submitted for this module emphasized the connection between mental clarity and physical vitality, which is a key component of the IPDC strategy for long-term productivity and happiness. The student's ability to balance rigorous academic requirements with self-care and spiritual discipline is highly commendable and serves as a model for their peers. <br><br>

        The behavioral analytics collected through peer reviews and faculty observations indicate a high degree of empathy, teamwork, and integrity. The student consistently displayed a "service-first" mindset, often taking the initiative in class discussions and community projects. This transcript also acknowledges the student's digital citizenship, noting their responsible use of technology and their advocacy for 'Digital Diet' practices within their social circles. The total of 1000 hours of institutional engagement recorded here reflects a journey of profound personal transformation. <br><br>

        In conclusion, this official transcript serves as a holistic representation of the student's character, academic merit, and leadership potential. The BAPS e-LMS certifies that the data contained herein is an accurate reflection of the student's achievements and moral progress. We believe that the values instilled through this course will serve as a lifelong foundation for the student's professional success and personal fulfillment. The institution takes great pride in the growth demonstrated by the student and recommends them for any leadership position that requires high ethical standards and a resilient spirit. This document is a secure record and is part of the student's permanent academic profile in the BAPS Master Archive.
    </div>

    <div class="section-title">VOLUNTEER SEVA & COMMUNITY IMPACT LOGS</div>
    <table class="module-table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Seva Activity Description</th>
                <th>Hours</th>
                <th>Impact Rating</th>
            </tr>
        </thead>
        <tbody>
            <tr><td>12 Oct 2024</td><td>Campus Sustainability & Cleaning Initiative</td><td>5.0</td><td>High</td></tr>
            <tr><td>25 Nov 2024</td><td>Digital Literacy Workshop for Senior Citizens</td><td>8.0</td><td>Exceptional</td></tr>
            <tr><td>15 Jan 2025</td><td>Food Distribution Drive (BAPS Relief Wing)</td><td>10.0</td><td>High</td></tr>
            <tr><td>05 Mar 2025</td><td>IPDC Peer Mentoring & Tutoring</td><td>12.0</td><td>High</td></tr>
        </tbody>
    </table>

    <div class="footer">
        <table class="signature-grid">
            <tr>
                <td>
                    <div class="sig-line"></div>
                    <strong>BAPS Kothari Shree</strong><br>Vice Chancellor
                </td>
                <td>
                    <div class="sig-line"></div>
                    <strong>Dean / Admin / HOD</strong><br>Institutional Oversight
                </td>
                <td>
                    <div class="sig-line"></div>
                    <strong>Instructor / Faculty</strong><br>Lead Mentor
                </td>
            </tr>
        </table>
        <div style="text-align: center; margin-top: 20px; font-size: 8px; color: #94a3b8;">
            Document ID: TRNS-{{ strtoupper(Str::random(12)) }} | Generated via BAPS e-LMS Secure Node | Verifiable via institutional portal.
        </div>
    </div>
</body>
</html>
