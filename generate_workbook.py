from pathlib import Path
from textwrap import wrap
import math

from PIL import Image, ImageDraw, ImageFont
from reportlab.lib import colors
from reportlab.lib.pagesizes import A4
from reportlab.lib.styles import ParagraphStyle, getSampleStyleSheet
from reportlab.lib.units import inch
from reportlab.pdfbase.pdfmetrics import stringWidth
from reportlab.platypus import (
    Image as RLImage,
    PageBreak,
    Paragraph,
    SimpleDocTemplate,
    Spacer,
    Table,
    TableStyle,
)


BASE_DIR = Path(__file__).resolve().parent
ASSET_DIR = BASE_DIR / "generated_assets"
WORKBOOK_PDF = BASE_DIR / "BAPS-IPDC-WORKBOOK-ITMBU-ED-1_VAISVIK-EKTA-SAMP-SEVA.pdf"
CODE_PDF = BASE_DIR / "CODE-VALI-PDF_BAPS-IPDC-WORKBOOK-GENERATOR.pdf"

PAGE_SIZE = A4
PAGE_WIDTH, PAGE_HEIGHT = PAGE_SIZE


THEME = {
    "navy": colors.HexColor("#12324A"),
    "saffron": colors.HexColor("#E46D2E"),
    "green": colors.HexColor("#2D7D5F"),
    "gold": colors.HexColor("#F1B54A"),
    "cream": colors.HexColor("#FFF8EA"),
    "light": colors.HexColor("#F4F7F8"),
    "ink": colors.HexColor("#1F2A33"),
    "muted": colors.HexColor("#5C6670"),
}


IMAGE_SPECS = [
    (
        "01_global_circle.png",
        "Global unity circle",
        "Students from many cultures standing around the earth.",
    ),
    (
        "02_samp_bridge.png",
        "Samp bridge",
        "A bridge built from listening, respect, forgiveness, and teamwork.",
    ),
    (
        "03_seva_hands.png",
        "Seva hands",
        "Hands serving food, books, water, and care.",
    ),
    (
        "04_value_tree.png",
        "Value tree",
        "Roots of humility and branches of unity, harmony, and service.",
    ),
    (
        "05_classroom_circle.png",
        "Classroom circle",
        "A classroom reflection circle for peaceful discussion.",
    ),
    (
        "06_service_map.png",
        "Service map",
        "A local to global seva map.",
    ),
    (
        "07_conflict_to_samp.png",
        "Conflict to samp",
        "A simple pathway from ego to understanding.",
    ),
    (
        "08_daily_practice.png",
        "Daily practice wheel",
        "Small daily habits for ekta, samp, and seva.",
    ),
    (
        "09_team_project.png",
        "Team project",
        "Students planning a seva project together.",
    ),
    (
        "10_final_pledge.png",
        "Final pledge",
        "A pledge card surrounded by symbols of unity and service.",
    ),
]


def get_font(size=28, bold=False):
    font_candidates = [
        "C:/Windows/Fonts/arialbd.ttf" if bold else "C:/Windows/Fonts/arial.ttf",
        "C:/Windows/Fonts/calibrib.ttf" if bold else "C:/Windows/Fonts/calibri.ttf",
    ]
    for candidate in font_candidates:
        if Path(candidate).exists():
            return ImageFont.truetype(candidate, size)
    return ImageFont.load_default()


def draw_wrapped(draw, text, box, font, fill, line_spacing=8, align="center"):
    x1, y1, x2, y2 = box
    max_width = x2 - x1
    words = text.split()
    lines = []
    line = ""
    for word in words:
        test = (line + " " + word).strip()
        if draw.textlength(test, font=font) <= max_width:
            line = test
        else:
            if line:
                lines.append(line)
            line = word
    if line:
        lines.append(line)
    line_height = font.size + line_spacing
    total_height = len(lines) * line_height
    y = y1 + max(0, (y2 - y1 - total_height) / 2)
    for line in lines:
        width = draw.textlength(line, font=font)
        if align == "center":
            x = x1 + (max_width - width) / 2
        elif align == "right":
            x = x2 - width
        else:
            x = x1
        draw.text((x, y), line, fill=fill, font=font)
        y += line_height


def rounded_card(draw, xy, fill, outline=None, radius=30, width=4):
    draw.rounded_rectangle(xy, radius=radius, fill=fill, outline=outline, width=width)


def save_image(name, title, subtitle, theme_index):
    ASSET_DIR.mkdir(exist_ok=True)
    path = ASSET_DIR / name
    img = Image.new("RGB", (1200, 760), "#FFF8EA")
    d = ImageDraw.Draw(img)

    palette = [
        ("#12324A", "#E46D2E", "#2D7D5F", "#F1B54A"),
        ("#214E77", "#35A79C", "#F2C14E", "#DF6C4F"),
        ("#2E4057", "#66A182", "#F6AE2D", "#8D99AE"),
        ("#1F363D", "#40798C", "#70A9A1", "#F6D8AE"),
    ][theme_index % 4]
    dark, accent, green, gold = palette

    d.rectangle((0, 0, 1200, 760), fill="#FFF8EA")
    d.rectangle((0, 0, 1200, 90), fill=dark)
    d.text((55, 22), title, fill="white", font=get_font(38, True))
    d.text((55, 640), subtitle, fill=dark, font=get_font(28, False))

    if "01_" in name:
        d.ellipse((395, 145, 805, 555), fill="#A9D6E5", outline=dark, width=7)
        d.arc((435, 180, 780, 520), 205, 340, fill=green, width=18)
        d.arc((430, 175, 780, 510), 25, 160, fill=accent, width=18)
        d.arc((500, 220, 715, 480), 100, 280, fill=gold, width=14)
        for x, y, c in [(220, 230, accent), (960, 230, green), (250, 510, gold), (930, 510, "#DF6C4F")]:
            d.ellipse((x - 45, y - 45, x + 45, y + 45), fill=c, outline=dark, width=5)
            d.line((x, y + 45, x, y + 130), fill=dark, width=8)
            d.line((x - 55, y + 90, x + 55, y + 90), fill=dark, width=7)
            d.line((x, y + 130, x - 45, y + 190), fill=dark, width=7)
            d.line((x, y + 130, x + 45, y + 190), fill=dark, width=7)
    elif "02_" in name:
        for i, word in enumerate(["Listening", "Respect", "Forgiveness", "Teamwork"]):
            x = 145 + i * 230
            rounded_card(d, (x, 335, x + 185, 455), "#FFFFFF", outline=dark, radius=22)
            draw_wrapped(d, word, (x + 14, 355, x + 171, 440), get_font(26, True), dark)
        d.line((150, 455, 1050, 455), fill=dark, width=16)
        d.arc((130, 240, 1070, 690), 190, 350, fill=accent, width=18)
        d.text((460, 175), "SAMP", fill=accent, font=get_font(72, True))
    elif "03_" in name:
        items = [("Food", 210, 260, accent), ("Books", 430, 390, green), ("Water", 650, 260, gold), ("Care", 870, 390, "#DF6C4F")]
        for label, x, y, c in items:
            d.rounded_rectangle((x, y, x + 145, y + 105), radius=25, fill=c, outline=dark, width=5)
            draw_wrapped(d, label, (x + 8, y + 28, x + 137, y + 80), get_font(28, True), "white")
            d.line((x + 70, y + 105, 600, 555), fill=dark, width=8)
        d.ellipse((500, 500, 700, 700), fill=green, outline=dark, width=6)
        draw_wrapped(d, "SEVA", (510, 555, 690, 635), get_font(38, True), "white")
    elif "04_" in name:
        d.rectangle((560, 340, 640, 605), fill="#8B5E34")
        d.polygon([(600, 145), (300, 400), (900, 400)], fill=green, outline=dark)
        d.polygon([(600, 225), (380, 470), (820, 470)], fill="#66A182", outline=dark)
        for label, x, y in [("Humility", 230, 585), ("Faith", 500, 625), ("Discipline", 765, 585)]:
            d.line((600, 600, x + 80, y), fill=dark, width=8)
            draw_wrapped(d, label, (x, y, x + 170, y + 55), get_font(24, True), dark)
        for label, x, y in [("Ekta", 360, 300), ("Samp", 535, 225), ("Seva", 710, 300)]:
            draw_wrapped(d, label, (x, y, x + 130, y + 60), get_font(30, True), "white")
    elif "05_" in name:
        center = (600, 385)
        for i, c in enumerate([accent, green, gold, "#DF6C4F", "#6A7FDB", "#35A79C", "#A7754D", "#7BA05B"]):
            angle = math.radians(i * 45)
            x = center[0] + 280 * math.cos(angle)
            y = center[1] + 190 * math.sin(angle)
            d.ellipse((x - 45, y - 45, x + 45, y + 45), fill=c, outline=dark, width=4)
        d.ellipse((415, 260, 785, 510), fill="white", outline=dark, width=7)
        draw_wrapped(d, "Speak with respect. Listen to understand.", (455, 315, 745, 450), get_font(32, True), dark)
    elif "06_" in name:
        d.line((190, 545, 1010, 210), fill=dark, width=10)
        points = [("Home", 170, 520, accent), ("Campus", 390, 430, green), ("City", 610, 340, gold), ("Nation", 830, 250, "#DF6C4F"), ("World", 1010, 175, "#6A7FDB")]
        for label, x, y, c in points:
            d.ellipse((x - 58, y - 58, x + 58, y + 58), fill=c, outline=dark, width=5)
            draw_wrapped(d, label, (x - 52, y - 20, x + 52, y + 25), get_font(24, True), "white")
    elif "07_" in name:
        boxes = [("Pause", 130, accent), ("Listen", 360, green), ("Understand", 590, gold), ("Unite", 840, "#DF6C4F")]
        for label, x, c in boxes:
            rounded_card(d, (x, 320, x + 180, 445), c, outline=dark, radius=25)
            draw_wrapped(d, label, (x + 10, 350, x + 170, 415), get_font(28, True), "white")
            if x < 840:
                d.line((x + 185, 383, x + 225, 383), fill=dark, width=8)
                d.polygon([(x + 225, 383), (x + 205, 365), (x + 205, 401)], fill=dark)
        d.text((190, 210), "From conflict to samp", fill=dark, font=get_font(48, True))
    elif "08_" in name:
        d.ellipse((360, 160, 840, 640), fill="white", outline=dark, width=8)
        habits = ["Greet", "Share", "Serve", "Reflect", "Forgive", "Thank"]
        for i, label in enumerate(habits):
            angle = math.radians(i * 60 - 90)
            x = 600 + 190 * math.cos(angle)
            y = 400 + 190 * math.sin(angle)
            d.ellipse((x - 55, y - 55, x + 55, y + 55), fill=palette[i % 4], outline=dark, width=4)
            draw_wrapped(d, label, (x - 48, y - 18, x + 48, y + 22), get_font(21, True), "white")
        draw_wrapped(d, "Daily Values", (500, 355, 700, 435), get_font(34, True), dark)
    elif "09_" in name:
        d.rounded_rectangle((285, 185, 915, 570), radius=35, fill="white", outline=dark, width=7)
        d.rectangle((335, 240, 865, 500), fill="#EAF4F4", outline=dark, width=4)
        for x, c in [(260, accent), (420, green), (780, gold), (940, "#DF6C4F")]:
            d.ellipse((x - 45, 565, x + 45, 655), fill=c, outline=dark, width=4)
        for label, y in [("Need", 275), ("Plan", 350), ("Serve", 425)]:
            d.text((385, y), label, fill=dark, font=get_font(32, True))
            d.line((540, y + 20, 780, y + 20), fill=accent, width=5)
    elif "10_" in name:
        d.rounded_rectangle((335, 165, 865, 575), radius=30, fill="white", outline=dark, width=7)
        d.text((475, 225), "MY PLEDGE", fill=dark, font=get_font(48, True))
        for y in [320, 385, 450]:
            d.line((430, y, 770, y), fill=accent, width=5)
        for x, y, c in [(260, 245, accent), (940, 245, green), (260, 540, gold), (940, 540, "#DF6C4F")]:
            d.ellipse((x - 48, y - 48, x + 48, y + 48), fill=c, outline=dark, width=4)
    else:
        d.ellipse((420, 180, 780, 540), fill=accent, outline=dark, width=8)

    img.save(path, quality=95)
    return path


def generate_images():
    paths = []
    for index, (name, title, subtitle) in enumerate(IMAGE_SPECS):
        paths.append(save_image(name, title, subtitle, index))
    return paths


def make_styles():
    styles = getSampleStyleSheet()
    styles.add(
        ParagraphStyle(
            name="CoverTitle",
            parent=styles["Title"],
            fontName="Helvetica-Bold",
            fontSize=27,
            leading=33,
            textColor=THEME["navy"],
            alignment=1,
            spaceAfter=16,
        )
    )
    styles.add(
        ParagraphStyle(
            name="PageTitle",
            parent=styles["Heading1"],
            fontName="Helvetica-Bold",
            fontSize=18,
            leading=23,
            textColor=THEME["navy"],
            spaceAfter=8,
        )
    )
    styles.add(
        ParagraphStyle(
            name="SubTitle",
            parent=styles["Heading2"],
            fontName="Helvetica-Bold",
            fontSize=13,
            leading=17,
            textColor=THEME["green"],
            spaceBefore=6,
            spaceAfter=4,
        )
    )
    styles.add(
        ParagraphStyle(
            name="Body",
            parent=styles["BodyText"],
            fontName="Helvetica",
            fontSize=10.5,
            leading=15,
            textColor=THEME["ink"],
            spaceAfter=6,
        )
    )
    styles.add(
        ParagraphStyle(
            name="Small",
            parent=styles["BodyText"],
            fontName="Helvetica",
            fontSize=8.5,
            leading=11,
            textColor=THEME["muted"],
        )
    )
    styles.add(
        ParagraphStyle(
            name="Quote",
            parent=styles["BodyText"],
            fontName="Helvetica-Oblique",
            fontSize=11,
            leading=16,
            leftIndent=12,
            rightIndent=12,
            textColor=THEME["navy"],
            borderColor=THEME["gold"],
            borderWidth=1,
            borderPadding=8,
            backColor=colors.HexColor("#FFF2D0"),
            spaceBefore=6,
            spaceAfter=8,
        )
    )
    return styles


def para(text, style):
    return Paragraph(text, style)


def worksheet_lines(count=7, height=0.33 * inch):
    rows = [[""] for _ in range(count)]
    table = Table(rows, colWidths=[6.7 * inch], rowHeights=[height] * count)
    table.setStyle(
        TableStyle(
            [
                ("LINEBELOW", (0, 0), (-1, -1), 0.5, colors.HexColor("#B8C2C8")),
                ("VALIGN", (0, 0), (-1, -1), "BOTTOM"),
            ]
        )
    )
    return table


def box_table(items, col_widths=None, row_height=0.55 * inch):
    data = [[para(item, make_styles()["Body"]) for item in row] for row in items]
    if col_widths is None:
        col_widths = [2.25 * inch] * len(items[0])
    table = Table(data, colWidths=col_widths, rowHeights=[row_height] * len(data))
    table.setStyle(
        TableStyle(
            [
                ("BOX", (0, 0), (-1, -1), 0.8, THEME["green"]),
                ("INNERGRID", (0, 0), (-1, -1), 0.4, colors.HexColor("#CBD4D8")),
                ("BACKGROUND", (0, 0), (-1, 0), colors.HexColor("#EAF4F4")),
                ("VALIGN", (0, 0), (-1, -1), "TOP"),
                ("LEFTPADDING", (0, 0), (-1, -1), 7),
                ("RIGHTPADDING", (0, 0), (-1, -1), 7),
                ("TOPPADDING", (0, 0), (-1, -1), 7),
                ("BOTTOMPADDING", (0, 0), (-1, -1), 7),
            ]
        )
    )
    return table


def add_image(story, image_path, caption):
    story.append(Spacer(1, 0.05 * inch))
    story.append(RLImage(str(image_path), width=6.25 * inch, height=3.95 * inch))
    story.append(para(caption, make_styles()["Small"]))
    story.append(Spacer(1, 0.08 * inch))


def page_header(canvas, doc):
    canvas.saveState()
    page = canvas.getPageNumber()
    canvas.setFillColor(THEME["navy"])
    canvas.rect(0, PAGE_HEIGHT - 0.38 * inch, PAGE_WIDTH, 0.38 * inch, fill=1, stroke=0)
    canvas.setFillColor(colors.white)
    canvas.setFont("Helvetica-Bold", 8.5)
    canvas.drawString(0.55 * inch, PAGE_HEIGHT - 0.25 * inch, "BAPS-IPDC-WORKBOOK-ITMBU-ED-1")
    canvas.drawRightString(PAGE_WIDTH - 0.55 * inch, PAGE_HEIGHT - 0.25 * inch, "Vaisvik Ekta | Samp | Seva")
    canvas.setFillColor(THEME["muted"])
    canvas.setFont("Helvetica", 8)
    canvas.drawCentredString(PAGE_WIDTH / 2, 0.35 * inch, f"Page {page}")
    canvas.restoreState()


def cover_canvas(canvas, doc):
    canvas.saveState()
    canvas.setFillColor(colors.HexColor("#FFF8EA"))
    canvas.rect(0, 0, PAGE_WIDTH, PAGE_HEIGHT, fill=1, stroke=0)
    canvas.setFillColor(THEME["navy"])
    canvas.rect(0, PAGE_HEIGHT - 1.7 * inch, PAGE_WIDTH, 1.7 * inch, fill=1, stroke=0)
    canvas.setFillColor(THEME["saffron"])
    canvas.circle(1.0 * inch, PAGE_HEIGHT - 0.86 * inch, 0.28 * inch, fill=1, stroke=0)
    canvas.setFillColor(THEME["gold"])
    canvas.circle(PAGE_WIDTH - 1.0 * inch, PAGE_HEIGHT - 0.86 * inch, 0.28 * inch, fill=1, stroke=0)
    canvas.setFillColor(THEME["navy"])
    canvas.setFont("Helvetica", 8)
    canvas.drawCentredString(PAGE_WIDTH / 2, 0.35 * inch, "Original educational workbook generated with Python")
    canvas.restoreState()


def page(title, body, activities=None, image=None, caption=None, quote=None):
    styles = make_styles()
    story = [para(title, styles["PageTitle"])]
    if image:
        add_image(story, image, caption or "")
    if quote:
        story.append(para(quote, styles["Quote"]))
    for section_title, paragraphs in body:
        story.append(para(section_title, styles["SubTitle"]))
        for text in paragraphs:
            story.append(para(text, styles["Body"]))
    if activities:
        story.append(para("Workbook Activity", styles["SubTitle"]))
        for text in activities:
            story.append(para(text, styles["Body"]))
        story.append(worksheet_lines(activities.count("line") if "line" in activities else 6))
    story.append(PageBreak())
    return story


def create_workbook(image_paths):
    styles = make_styles()
    doc = SimpleDocTemplate(
        str(WORKBOOK_PDF),
        pagesize=PAGE_SIZE,
        rightMargin=0.55 * inch,
        leftMargin=0.55 * inch,
        topMargin=0.68 * inch,
        bottomMargin=0.62 * inch,
        title="BAPS-IPDC Workbook 1",
    )

    story = []
    story.append(Spacer(1, 1.35 * inch))
    story.append(para("BAPS-IPDC-WORKBOOK-ITMBU-ED-1", styles["CoverTitle"]))
    story.append(para("Workbook 1", styles["CoverTitle"]))
    story.append(para("Topic: Vaisvik Ekta, Samp and Seva", styles["CoverTitle"]))
    story.append(Spacer(1, 0.35 * inch))
    story.append(para("A 30-page student workbook for reflection, classroom discussion, and personal practice.", styles["Body"]))
    story.append(Spacer(1, 0.25 * inch))
    story.append(RLImage(str(image_paths[0]), width=6.25 * inch, height=3.95 * inch))
    story.append(Spacer(1, 0.15 * inch))
    story.append(para("Name: ___________________________   Class: ___________   Date: ___________", styles["Body"]))
    story.append(PageBreak())

    pages = [
        (
            "2. How to Use This Workbook",
            [("Purpose", ["This workbook helps students understand three connected values: Vaisvik Ekta, Samp, and Seva. Read each short lesson, discuss honestly, and complete the reflection tasks with sincerity."]),
             ("Learning Method", ["Each page includes a concept, a classroom connection, and an action step. The aim is not only to know the words, but to practise them in daily life."])],
            ["Write one personal goal for this workbook.", "line"],
            None,
            None,
            "Values become strong when understanding becomes action.",
        ),
        (
            "3. Key Words",
            [("Vaisvik Ekta", ["Vaisvik Ekta means global unity: seeing all people as part of one human family while respecting different languages, cultures, and traditions."]),
             ("Samp", ["Samp means harmony, togetherness, and unity in thought, speech, and action. It asks us to reduce ego and build trust."]),
             ("Seva", ["Seva means selfless service. It is helping without expecting reward, recognition, or control."])],
            ["In your own words, define each value in one sentence.", "line"],
            None,
            None,
            None,
        ),
        (
            "4. Vaisvik Ekta: One Human Family",
            [("Concept", ["Global unity begins with the thought that every person has dignity. We may differ in dress, food, language, or habits, but we share the same need for respect, peace, and belonging."]),
             ("Classroom Connection", ["A class becomes a small model of the world. When students include someone who is quiet, new, or different, they practise global unity in a real way."])],
            ["List three ways your class can show global unity this week.", "line"],
            image_paths[0],
            "Image 1: Students around the earth as one human family.",
            None,
        ),
        (
            "5. Seeing Beyond Labels",
            [("Reflection", ["Labels can be useful, but they can also limit how we see people. Vaisvik Ekta teaches us to look beyond labels and notice character, effort, and inner goodness."]),
             ("Practice", ["Before judging someone, pause and ask: What do I not know about their story? This question opens the door to empathy."])],
            ["Write about a time when you changed your opinion after understanding someone better.", "line"],
            None,
            None,
            None,
        ),
        (
            "6. Samp: The Bridge of Harmony",
            [("Concept", ["Samp is not silence. It is the courage to speak truth with respect and the maturity to listen without anger."]),
             ("Four Bridge Stones", ["A strong bridge of samp is built with listening, respect, forgiveness, and teamwork. If one stone is missing, relationships become weak."])],
            ["Which bridge stone is strongest in you? Which one needs practice?", "line"],
            image_paths[1],
            "Image 2: The bridge stones of samp.",
            None,
        ),
        (
            "7. Harmony in Speech",
            [("Think Before Speaking", ["Words can repair or damage trust. A samp-filled sentence is truthful, kind, necessary, and timed well."]),
             ("Sentence Practice", ["Instead of saying, 'You never help,' say, 'Can we divide this task more fairly?' The second sentence solves the problem without attacking the person."])],
            ["Rewrite three harsh sentences into samp-filled sentences.", "line"],
            None,
            None,
            None,
        ),
        (
            "8. Seva: Service With a Pure Heart",
            [("Concept", ["Seva is not only a big project. It can be a small act done with a pure heart: helping a friend understand homework, cleaning a shared space, or caring for someone who is tired."]),
             ("Inner Attitude", ["True seva asks: What is needed? How can I help? Can I serve without making myself the centre?"])],
            ["Describe one small seva you can do at home and one at school.", "line"],
            image_paths[2],
            "Image 3: Seva through food, books, water, and care.",
            "Selfless service turns ordinary moments into meaningful moments.",
        ),
        (
            "9. Why These Three Values Belong Together",
            [("Connection", ["Vaisvik Ekta gives us the vision: everyone belongs. Samp gives us the method: live in harmony. Seva gives us the action: help others with humility."]),
             ("Example", ["If a class wants to serve a community, it first needs unity of vision, harmony in planning, and selfless effort in execution."])],
            ["Complete this chain: When I believe everyone belongs, I will...", "line"],
            None,
            None,
            None,
        ),
        (
            "10. The Value Tree",
            [("Roots and Fruits", ["Good actions grow from deep roots. Humility, faith, discipline, and respect are roots. Unity, harmony, compassion, and service are fruits."]),
             ("Personal Growth", ["A tree grows slowly but steadily. In the same way, values grow through repeated practice, not one-time excitement."])],
            ["Draw or write your own value tree: roots, trunk, branches, and fruits.", "line"],
            image_paths[3],
            "Image 4: A value tree with roots and branches.",
            None,
        ),
        (
            "11. Listening Circle",
            [("Skill", ["A listening circle is a simple method: one person speaks, others listen, and nobody interrupts. After the speaker finishes, classmates may ask respectful questions."]),
             ("Why It Works", ["Listening circles reduce misunderstanding because everyone gets space to be heard. They also train patience and empathy."])],
            ["In a group, discuss: What makes students feel included?", "line"],
            image_paths[4],
            "Image 5: A classroom circle for respectful discussion.",
            None,
        ),
        (
            "12. Empathy Practice",
            [("Definition", ["Empathy means trying to understand another person's feelings and situation. It does not mean we agree with everything, but it means we care enough to understand."]),
             ("Practice Step", ["Use the sentence: 'I can see that you felt ___ because ___.' This helps another person feel heard."])],
            ["Fill in the empathy sentence for two school situations.", "line"],
            None,
            None,
            None,
        ),
        (
            "13. Seva From Local to Global",
            [("Scope", ["Seva begins nearby, but its spirit can reach the whole world. A clean classroom, a helped neighbour, a campus drive, or a relief project all carry the same spirit."]),
             ("Responsibility", ["Global unity becomes real when people take responsibility for needs beyond their own comfort."])],
            ["Mark one need at home, on campus, in the city, and in the world.", "line"],
            image_paths[5],
            "Image 6: Seva expanding from home to the world.",
            None,
        ),
        (
            "14. Conflict to Samp",
            [("Conflict", ["Conflict is natural when people work together. The question is whether conflict makes us bitter or wiser."]),
             ("Path", ["Pause before reacting. Listen to the other side. Understand the need behind the words. Then unite around the shared goal."])],
            ["Choose one conflict and map it through Pause, Listen, Understand, Unite.", "line"],
            image_paths[6],
            "Image 7: A simple path from conflict to samp.",
            None,
        ),
        (
            "15. Ego Check",
            [("Self-Questioning", ["Many problems grow when ego becomes bigger than the mission. A useful question is: Am I trying to solve the problem, or only trying to prove I am right?"]),
             ("Humility", ["Humility does not make a person weak. It makes teamwork possible."])],
            ["Write three signs that ego is entering a discussion.", "line"],
            None,
            None,
            None,
        ),
        (
            "16. Daily Practice Wheel",
            [("Small Habits", ["Values become strong through daily habits. Greet people warmly, share credit, serve quietly, reflect honestly, forgive quickly, and thank sincerely."]),
             ("Challenge", ["Choose one habit for the next seven days. Track your effort each evening."])],
            ["Select one daily habit and write how you will practise it.", "line"],
            image_paths[7],
            "Image 8: A wheel of daily value habits.",
            None,
        ),
        (
            "17. Case Study: The Group Assignment",
            [("Situation", ["Four students must submit a project. Two want to lead, one feels ignored, and one has not completed their part."]),
             ("Apply Values", ["Vaisvik Ekta reminds them that each member matters. Samp helps them speak calmly. Seva encourages each person to ask, 'What can I contribute now?'"])],
            ["What should the group do in the next 15 minutes?", "line"],
            None,
            None,
            None,
        ),
        (
            "18. Planning a Seva Project",
            [("Project Steps", ["A seva project needs a real need, a clear plan, shared roles, a respectful attitude, and reflection after completion."]),
             ("Team Spirit", ["The best project is not the one with the most praise. It is the one that genuinely helps and makes the team more united."])],
            ["Write a project idea with need, team roles, materials, and timeline.", "line"],
            image_paths[8],
            "Image 9: Students planning a seva project.",
            None,
        ),
        (
            "19. Seva Project Template",
            [("Use This Template", ["Complete the boxes before starting your project. Keep the plan simple, realistic, and respectful."])],
            None,
            None,
            None,
            None,
            None,
        ),
        (
            "20. Reflection After Service",
            [("After Action", ["Reflection turns activity into learning. After seva, ask what went well, what could improve, who was helped, and how the team changed."]),
             ("Gratitude", ["Thank the people who allowed you to serve, the team members who helped, and the mentors who guided you."])],
            ["Write one lesson from service that you want to remember.", "line"],
            None,
            None,
            None,
        ),
        (
            "21. Role Models of Unity and Service",
            [("Observation", ["A role model is not only famous. A role model can be a parent, teacher, friend, volunteer, or classmate who quietly lives these values."]),
             ("Study Method", ["Notice the person's action, attitude, and consistency. Then choose one quality to practise yourself."])],
            ["Name a role model and describe one value you see in them.", "line"],
            None,
            None,
            None,
        ),
        (
            "22. Communication Lab",
            [("Three Tools", ["Use 'I' statements, ask clarifying questions, and summarise what you heard before replying. These tools protect samp in difficult conversations."]),
             ("Practice", ["When emotions are high, speak slower and softer. A calm tone can keep the discussion open."])],
            ["Write a dialogue where two students solve a disagreement peacefully.", "line"],
            None,
            None,
            None,
        ),
        (
            "23. Inclusion Audit",
            [("Audit", ["An inclusion audit checks whether everyone gets a fair chance to join, speak, lead, and feel respected."]),
             ("Action", ["Do not wait for excluded students to ask for help. Invite them with warmth and dignity."])],
            ["Rate your class from 1 to 5 on inclusion. Give one improvement idea.", "line"],
            None,
            None,
            None,
        ),
        (
            "24. My Weekly Tracker",
            [("Tracker", ["Track your actions for one week. Be honest. The tracker is not for showing off; it is for becoming aware."])],
            None,
            None,
            None,
            None,
            None,
        ),
        (
            "25. Quiz: Concepts",
            [("Answer Briefly", ["Use your own words. Focus on meaning and practical application."])],
            ["1. What is Vaisvik Ekta?", "2. What are four bridge stones of Samp?", "3. Why should Seva be done without expectation?", "4. How can conflict become a chance for growth?", "line"],
            None,
            None,
            None,
        ),
        (
            "26. Scenario Practice",
            [("Choose the Best Response", ["For each scenario, write a response that shows unity, harmony, and service."])],
            ["A new student sits alone.", "Your team member made a mistake.", "A campus area is dirty after an event.", "Two friends are not talking to each other.", "line"],
            None,
            None,
            None,
        ),
        (
            "27. Personal Action Plan",
            [("Plan", ["A good plan is clear enough to start today. Choose one action for each value and one person who can support you."])],
            ["My action for Vaisvik Ekta:", "My action for Samp:", "My action for Seva:", "line"],
            None,
            None,
            None,
        ),
        (
            "28. Group Pledge",
            [("Together", ["A pledge becomes powerful when a group supports one another. Read the pledge aloud and discuss what it will look like in real behaviour."])],
            ["As a group, write five promises for a more united and service-minded class.", "line"],
            image_paths[9],
            "Image 10: A pledge card for unity, harmony, and service.",
            None,
        ),
        (
            "29. Final Reflection",
            [("Look Back", ["Review your answers from the workbook. Circle one idea that changed your thinking and one action that changed your behaviour."]),
             ("Look Forward", ["Values education is successful when it continues after the workbook closes. Choose one practice to continue for the next month."])],
            ["The biggest lesson I learned is...", "The value I need most now is...", "The action I will continue is...", "line"],
            None,
            None,
            None,
        ),
        (
            "30. Completion Page",
            [("Student Statement", ["I have studied Vaisvik Ekta, Samp, and Seva. I understand that unity, harmony, and selfless service must be practised in thoughts, words, and actions."]),
             ("Teacher/Mentor Note", ["Use this page for feedback, encouragement, or a next-step suggestion."])],
            ["Student signature: ____________________", "Teacher/Mentor signature: ______________", "Date: ____________________", "line"],
            None,
            None,
            "May my thoughts support unity, my words support harmony, and my actions support service.",
        ),
    ]

    for index, page_data in enumerate(pages, start=2):
        # Unpack up to 6 items, filling with None if missing
        title = page_data[0]
        body = page_data[1]
        activities = page_data[2]
        image = page_data[3]
        caption = page_data[4]
        quote = page_data[5] if len(page_data) > 5 else None
        
        if title.startswith("19."):
            story.extend(page(title, body, None, image, caption, quote)[:-1])
            story.append(
                box_table(
                    [
                        ["Need we will serve", "Who will benefit", "Why it matters"],
                        ["", "", ""],
                        ["Team roles", "Materials needed", "Date and place"],
                        ["", "", ""],
                    ],
                    row_height=0.75 * inch,
                )
            )
            story.append(Spacer(1, 0.15 * inch))
            story.append(worksheet_lines(5))
            story.append(PageBreak())
        elif title.startswith("24."):
            story.extend(page(title, body, None, image, caption, quote)[:-1])
            story.append(
                box_table(
                    [
                        ["Day", "Ekta action", "Samp action", "Seva action"],
                        ["Mon", "", "", ""],
                        ["Tue", "", "", ""],
                        ["Wed", "", "", ""],
                        ["Thu", "", "", ""],
                        ["Fri", "", "", ""],
                        ["Sat", "", "", ""],
                        ["Sun", "", "", ""],
                    ],
                    col_widths=[0.85 * inch, 1.95 * inch, 1.95 * inch, 1.95 * inch],
                    row_height=0.43 * inch,
                )
            )
            story.append(PageBreak())
        else:
            story.extend(page(title, body, activities, image, caption, quote))

    if isinstance(story[-1], PageBreak):
        story.pop()

    doc.build(story, onFirstPage=cover_canvas, onLaterPages=page_header)


def code_line_to_para(line, style):
    safe = (
        line.replace("&", "&amp;")
        .replace("<", "&lt;")
        .replace(">", "&gt;")
        .replace(" ", "&nbsp;")
    )
    return Paragraph(safe, style)


def create_code_pdf():
    styles = getSampleStyleSheet()
    styles.add(
        ParagraphStyle(
            name="CodeTitle",
            parent=styles["Title"],
            fontName="Helvetica-Bold",
            fontSize=18,
            textColor=THEME["navy"],
            spaceAfter=10,
        )
    )
    styles.add(
        ParagraphStyle(
            name="CodeBlockTiny",
            parent=styles["Code"],
            fontName="Courier",
            fontSize=6.2,
            leading=7.2,
            leftIndent=0,
            rightIndent=0,
            spaceAfter=0,
        )
    )
    doc = SimpleDocTemplate(
        str(CODE_PDF),
        pagesize=PAGE_SIZE,
        rightMargin=0.42 * inch,
        leftMargin=0.42 * inch,
        topMargin=0.52 * inch,
        bottomMargin=0.45 * inch,
        title="Code Wali PDF",
    )
    story = [
        Paragraph("Code Wali PDF: Workbook Generator", styles["CodeTitle"]),
        Paragraph(
            "This PDF contains the Python code used to generate the workbook PDF and its 10 original images.",
            styles["BodyText"],
        ),
        Spacer(1, 0.12 * inch),
    ]
    source = Path(__file__).read_text(encoding="utf-8").splitlines()
    for n, line in enumerate(source, start=1):
        numbered = f"{n:04d}: {line}"
        for chunk in wrap(numbered, width=112, replace_whitespace=False, drop_whitespace=False) or [""]:
            story.append(code_line_to_para(chunk, styles["CodeBlockTiny"]))
    doc.build(story, onFirstPage=page_header, onLaterPages=page_header)


def main():
    image_paths = generate_images()
    create_workbook(image_paths)
    create_code_pdf()
    print(f"Created: {WORKBOOK_PDF}")
    print(f"Created: {CODE_PDF}")
    print(f"Images: {ASSET_DIR}")


if __name__ == "__main__":
    main()
