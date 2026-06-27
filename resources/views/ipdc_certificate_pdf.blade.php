<!DOCTYPE html>
<html>
<head>
    <style>
        @page { size: a4 landscape; margin: 0; }
        body { font-family: 'Times New Roman', serif; color: #333; margin: 0; padding: 0; background: #fff; }
        .cert-container { 
            width: 100%; height: 100%; padding: 25px; box-sizing: border-box;
            border: 15px solid #f97316; /* Saffron Border */
            position: relative;
        }
        .inner-border {
            border: 2px solid #fdba74; height: 100%; padding: 20px; box-sizing: border-box;
        }
        .header { text-align: center; margin-bottom: 10px; }
        .logo { font-size: 26px; font-weight: bold; color: #f97316; margin-bottom: 2px; }
        .title { font-size: 38px; font-weight: bold; margin: 5px 0; color: #1e293b; text-transform: uppercase; }
        .subtitle { font-size: 16px; color: #64748b; margin-bottom: 15px; letter-spacing: 2px; }
        
        .content { text-align: center; line-height: 1.4; }
        .verify-text { font-size: 15px; font-style: italic; margin-bottom: 5px; }
        .name { font-size: 30px; font-weight: bold; color: #f97316; border-bottom: 2px solid #ccc; display: inline-block; padding: 0 40px; margin-bottom: 10px; }
        
        .description { 
            font-size: 11.5px; text-align: justify; margin: 10px 40px; color: #475569; 
            line-height: 1.3;
        }

        .footer { position: absolute; bottom: 40px; width: 100%; left: 0; padding: 0 80px; box-sizing: border-box; }
        .sig-block { float: left; width: 33.33%; text-align: center; }
        .sig-line { border-top: 1px solid #333; width: 80%; margin: 25px auto 5px auto; }
        .sig-name { font-size: 11px; font-weight: bold; }
        .sig-title { font-size: 9px; color: #64748b; }

        .qr-code { position: absolute; bottom: 40px; right: 80px; text-align: center; }
        .qr-img { width: 60px; height: 60px; background: #eee; display: block; margin-bottom: 5px; }
        .verify-id { font-size: 10px; font-family: monospace; }
    </style>
</head>
<body>
    <div class="cert-container">
        <div class="inner-border">
            <div class="header">
                <div class="logo">BAPS SWAMINARAYAN SANSTHA</div>
                <div class="title">Certificate of Merit</div>
                <div class="subtitle">INTEGRATED PERSONALITY DEVELOPMENT COURSE (IPDC)</div>
            </div>

            <div class="content">
                <div class="verify-text">This prestigious certificate is solemnly awarded to</div>
                <div class="name">{{ $certificate->user->name }}</div>
                
                <div class="description">
                    Pursuant to the rigorous academic standards and moral framework established by the BAPS Integrated Personality Development Course (IPDC), this document serves as an official testament that the aforementioned student has successfully demonstrated exceptional dedication, character resilience, and ethical leadership throughout the duration of the curriculum. The IPDC program, deeply rooted in the timeless wisdom of HH Pramukh Swami Maharaj and HH Mahant Swami Maharaj, is designed to empower the youth of the 21st century with the essential tools for facing failures with equanimity, fostering unbreakable family bonds, and cultivating a spirit of selfless service (Seva). <br><br>
                    
                    The candidate has completed comprehensive modules focusing on 'Facing Failures', 'Remaking Yourself', 'Learning from Legends', and 'Health and Wellness', showcasing a profound understanding of how to navigate the complexities of modern life while maintaining an unwavering moral compass. Through interactive workshops, reflective worksheet submissions, and active participation in community service initiatives, the student has not only acquired theoretical knowledge but has also applied these principles in practical, real-world scenarios. This certification acknowledges that the student has met the institutional benchmarks for value-based education, demonstrating a commitment to personal growth that transcends mere academic achievement. <br><br>
                    
                    Furthermore, the student's involvement in the 'Volunteer Seva' logs indicates a heart dedicated to the welfare of society, reflecting the institutional core value: "In the joy of others lies our own." The IPDC curriculum is a journey of self-discovery and transformation, and by receiving this award, the student is recognized as a future leader of integrity who possesses the character to contribute positively to their family, profession, and the global community. This certificate is valid indefinitely and represents a lifelong commitment to the values of honesty, hard work, and spiritual grounding. We commend the student for their pursuit of excellence and their decision to lead a life of purpose and virtue. This institutional recognition is backed by the BAPS educational archive and is verifiable through our secure portal using the unique identification code provided below. May this recognition serve as a constant reminder of the potential within to lead a life that inspires others.
                </div>
            </div>

            <div class="footer">
                <div class="sig-block">
                    <div class="sig-line"></div>
                    <div class="sig-name">Pujya Kothari Shree</div>
                    <div class="sig-title">Vice Chancellor / Kothari, BAPS</div>
                </div>
                <div class="sig-block">
                    <div class="sig-line"></div>
                    <div class="sig-name">Prof. Dr. Admin Panel</div>
                    <div class="sig-title">DEAN / Institutional HOD</div>
                </div>
                <div class="sig-block">
                    <div class="sig-line"></div>
                    <div class="sig-name">Academic Mentor</div>
                    <div class="sig-title">Lead IPDC Instructor</div>
                </div>
            </div>

            <div class="qr-code">
                <div class="qr-img">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data={{ $certificate->unique_code }}" style="width: 80px;">
                </div>
                <div class="verify-id">ID: {{ $certificate->unique_code }}</div>
            </div>
        </div>
    </div>
</body>
</html>
