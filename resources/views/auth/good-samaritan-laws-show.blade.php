@extends('layouts.auth')

@section('title', 'Good Samaritan Laws - Legal Protection for Overdose Response')

@section('breadcrumb', 'Good Samaritan Laws')

@section('content')
<style>
    .samaritan-page {
        max-width: 1000px;
        margin: 0 auto;
        padding: 0;
    }

    .samaritan-header {
        background: linear-gradient(135deg, #0d5b7c 0%, #1a7a99 100%);
        color: white;
        padding: 32px 24px;
        border-bottom: 3px solid #2a9db8;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px 8px 0 0;
    }

    .samaritan-header h1 {
        font-size: 32px;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .samaritan-header p {
        font-size: 16px;
        opacity: 0.9;
        margin: 0;
    }

    .samaritan-section {
        background-color: #ffffff;
        border-radius: 8px;
        padding: 32px;
        margin-bottom: 32px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        border: 1px solid #e0e0e0;
    }

    .samaritan-section h2 {
        color: #0d5b7c;
        font-size: 24px;
        margin-bottom: 16px;
        padding-bottom: 16px;
        border-bottom: 2px solid #2a9db8;
    }

    .samaritan-section h3 {
        color: #1a7a99;
        font-size: 18px;
        margin-top: 16px;
        margin-bottom: 12px;
        font-weight: 600;
    }

    .samaritan-section p {
        color: #666666;
        line-height: 1.8;
        margin-bottom: 16px;
        font-size: 15px;
    }

    .info-box {
        background-color: #f0f9fb;
        border-left: 4px solid #2a9db8;
        padding: 16px;
        border-radius: 6px;
        margin-bottom: 16px;
    }

    .info-box.warning {
        background-color: #fef3c7;
        border-left-color: #f59e0b;
    }

    .info-box.danger {
        background-color: #fee2e2;
        border-left-color: #dc2626;
    }

    .info-box.success {
        background-color: #dcfce7;
        border-left-color: #22c55e;
    }

    .info-box strong {
        color: #1a1a1a;
        display: block;
        margin-bottom: 8px;
        font-size: 16px;
    }

    .info-box p {
        margin: 0;
        font-size: 14px;
        color: #333333;
    }

    .samaritan-section ul,
    .samaritan-section ol {
        margin-left: 16px;
        margin-bottom: 16px;
    }

    .samaritan-section li {
        margin-bottom: 12px;
        color: #666666;
        line-height: 1.7;
    }

    .protection-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 16px;
        margin-bottom: 16px;
    }

    .protection-card {
        background-color: #f5f5f5;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        padding: 20px;
    }

    .protection-card h4 {
        color: #0d5b7c;
        font-size: 16px;
        margin: 0 0 12px 0;
        font-weight: 600;
    }

    .protection-card p {
        margin: 0 0 12px 0;
        font-size: 14px;
        color: #666666;
        line-height: 1.6;
    }

    .protection-card ul {
        margin-left: 0;
        padding-left: 20px;
        margin-bottom: 0;
    }

    .protection-card li {
        margin-bottom: 6px;
        font-size: 13px;
    }

    .state-overview {
        background-color: #f0f9fb;
        border: 2px solid #2a9db8;
        border-radius: 6px;
        padding: 20px;
        margin-bottom: 16px;
    }

    .state-overview h4 {
        color: #0d5b7c;
        font-size: 16px;
        margin: 0 0 12px 0;
        font-weight: 600;
    }

    .legal-comparison {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 16px;
    }

    .legal-comparison th {
        background-color: #0d5b7c;
        color: white;
        padding: 16px;
        text-align: left;
        font-weight: 600;
    }

    .legal-comparison td {
        padding: 16px;
        border-bottom: 1px solid #e0e0e0;
    }

    .legal-comparison tr:hover {
        background-color: #f5f5f5;
    }

    .recommendations-box {
        background-color: #dcfce7;
        border: 2px solid #22c55e;
        border-radius: 6px;
        padding: 20px;
        margin-bottom: 16px;
    }

    .recommendations-box h4 {
        color: #22c55e;
        font-size: 16px;
        margin: 0 0 12px 0;
        font-weight: 600;
    }

    .recommendations-box ul {
        margin: 0;
    }

    .myth-busting {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 16px;
        margin-bottom: 16px;
    }

    .myth-card {
        background-color: #fee2e2;
        border: 2px solid #dc2626;
        border-radius: 6px;
        padding: 16px;
    }

    .myth-card h4 {
        color: #dc2626;
        font-size: 15px;
        margin: 0 0 8px 0;
        font-weight: 600;
    }

    .myth-card p {
        margin: 0;
        font-size: 13px;
        color: #333333;
        line-height: 1.6;
    }

    .disclaimer-section {
        border: 2px solid #dc2626;
        background-color: #fee2e2;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .samaritan-header h1 {
            font-size: 24px;
        }

        .samaritan-header p {
            font-size: 14px;
        }

        .samaritan-section {
            padding: 16px;
        }

        .samaritan-section h2 {
            font-size: 20px;
        }

        .protection-grid {
            grid-template-columns: 1fr;
        }

        .myth-busting {
            grid-template-columns: 1fr;
        }

        .legal-comparison {
            font-size: 13px;
        }

        .legal-comparison th,
        .legal-comparison td {
            padding: 12px;
        }
    }

    @media (max-width: 480px) {
        .samaritan-header h1 {
            font-size: 20px;
        }

        .samaritan-section {
            padding: 12px;
        }

        .samaritan-section h2 {
            font-size: 18px;
        }

        .samaritan-section h3 {
            font-size: 16px;
        }

        .info-box {
            padding: 12px;
        }
    }
</style>

<div class="samaritan-page">
    <!-- Header -->
    <div class="samaritan-header">
        <h1>⚖️ Good Samaritan Laws</h1>
        <p>Legal protections for calling 911 during overdose emergencies. Understand your rights and protections.</p>
    </div>

    <!-- Core Concept -->
    <section class="samaritan-section">
        <h2>What Are Good Samaritan Laws?</h2>
        <p>Good Samaritan laws are legal protections designed to encourage people to seek emergency help during overdoses without fear of criminal prosecution. These laws protect both the person overdosing and those who call 911 from certain criminal charges related to drug possession or use.</p>
        
        <div class="info-box success">
            <strong>✓ Life-Saving Protections</strong>
            <p>Good Samaritan laws save lives by removing legal barriers to calling 911. People who might otherwise delay calling for fear of arrest can now act quickly to save a life.</p>
        </div>

        <h3>Core Principle</h3>
        <p>The basic idea: If you call 911 to report an overdose emergency, you and the person overdosing have legal protection from arrest for drug possession charges related to that specific situation. This encourages life-saving action.</p>
    </section>

    <!-- What's Protected -->
    <section class="samaritan-section">
        <h2>What's Protected Under Good Samaritan Laws</h2>
        
        <div class="protection-grid">
            <div class="protection-card">
                <h4>Calling 911</h4>
                <p>Protection for calling emergency services during an overdose. You're encouraged, not punished.</p>
                <ul>
                    <li>Can't be arrested for calling</li>
                    <li>True even if you use drugs</li>
                    <li>Protection extends to caller</li>
                </ul>
            </div>

            <div class="protection-card">
                <h4>Administering Naloxone</h4>
                <p>Lawful protection for giving Narcan during overdose emergency.</p>
                <ul>
                    <li>Can't be charged for giving Narcan</li>
                    <li>Protected from liability</li>
                    <li>Even if outcome is poor</li>
                </ul>
            </div>

            <div class="protection-card">
                <h4>Being Present</h4>
                <p>Protection for being at location where drugs present during emergency.</p>
                <ul>
                    <li>Can't be arrested for presence</li>
                    <li>Can't be arrested for possession in emergency situation</li>
                    <li>Specific to overdose emergency</li>
                </ul>
            </div>

            <div class="protection-card">
                <h4>Drug Possession</h4>
                <p>Limited protection from possession charges in overdose emergency context.</p>
                <ul>
                    <li>Varies by state</li>
                    <li>Some states have broad protection</li>
                    <li>Some have limited protection</li>
                </ul>
            </div>

            <div class="protection-card">
                <h4>Medical Liability</h4>
                <p>Protection from civil liability for attempting to help.</p>
                <ul>
                    <li>Protected from lawsuits in many states</li>
                    <li>Even if help unsuccessful</li>
                    <li>For reasonable emergency response</li>
                </ul>
            </div>

            <div class="protection-card">
                <h4>Paraphernalia</h4>
                <p>Limited protection related to drug paraphernalia during emergency.</p>
                <ul>
                    <li>Varies by jurisdiction</li>
                    <li>Some states offer broad protection</li>
                    <li>Some states limited</li>
                </ul>
            </div>
        </div>

        <div class="info-box warning">
            <strong>⚠️ Laws Vary by State</strong>
            <p>Good Samaritan law protections vary significantly between states. Some are comprehensive, some are limited. Check your specific state's laws. Even if limited, calling 911 is always the right choice.</p>
        </div>
    </section>

    <!-- What's NOT Protected -->
    <section class="samaritan-section">
        <h2>Important Limitations: What's NOT Protected</h2>
        
        <h3>Good Samaritan laws typically do NOT protect:</h3>
        <ul>
            <li><strong>Trafficking/Distribution:</strong> Selling or distributing drugs is not protected</li>
            <li><strong>Large Amounts:</strong> Possession with intent to distribute may not be protected</li>
            <li><strong>Outstanding Warrants:</strong> Pre-existing warrants may still be enforceable</li>
            <li><strong>Other Crimes:</strong> Crimes unrelated to the overdose (theft, violence, etc.)</li>
            <li><strong>Parole/Probation Violations:</strong> Drug use may violate parole conditions</li>
            <li><strong>Child Endangerment:</strong> If children endangered, separate charges may apply</li>
            <li><strong>Negligence:</strong> Gross negligence or recklessness may not be protected</li>
        </ul>

        <div class="info-box danger">
            <strong>🚨 No Protection Is Absolute</strong>
            <p>Good Samaritan protections are not absolute. Circumstances matter. Police retain discretion. However, these laws make arrest much less likely and prosecutors less likely to pursue charges.</p>
        </div>
    </section>

    <!-- State Coverage -->
    <section class="samaritan-section">
        <h2>Good Samaritan Law Coverage by State</h2>
        
        <div class="state-overview">
            <h4>Current Status (as of 2025)</h4>
            <p><strong>All 50 states have some form of Good Samaritan law protection</strong> for overdose response. However, protections vary significantly in scope:</p>
        </div>

        <table class="legal-comparison">
            <thead>
                <tr>
                    <th>Coverage Level</th>
                    <th>Description</th>
                    <th>Examples</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Comprehensive</strong></td>
                    <td>Broad protection for calling 911, naloxone, and drug possession in overdose context</td>
                    <td>New York, California, Illinois, Washington, Massachusetts</td>
                </tr>
                <tr>
                    <td><strong>Moderate</strong></td>
                    <td>Protection for calling 911 and naloxone; limited drug possession protection</td>
                    <td>Texas, Florida, Ohio, Georgia, Michigan</td>
                </tr>
                <tr>
                    <td><strong>Limited</strong></td>
                    <td>Protection for calling 911; minimal other protections</td>
                    <td>Varies; some Southern states have more limited protections</td>
                </tr>
            </tbody>
        </table>

        <div class="info-box success">
            <strong>✓ Progress Continues</strong>
            <p>Good Samaritan protections are expanding. More states are strengthening laws to save lives. Even if your state's protections are limited, calling 911 is always the right choice.</p>
        </div>
    </section>

    <!-- How to Use Protection -->
    <section class="samaritan-section">
        <h2>How to Protect Yourself When Calling 911</h2>
        
        <div class="recommendations-box">
            <h4>✓ Best Practices for Calling 911</h4>
            <ul>
                <li><strong>Call First:</strong> Prioritize calling 911 before administering aid. Clear it's an overdose emergency</li>
                <li><strong>Be Clear:</strong> Tell dispatcher it's an overdose. Give location and information about person's condition</li>
                <li><strong>Stay on Line:</strong> Follow dispatcher's instructions. Stay on the line until help arrives</li>
                <li><strong>Remain Present:</strong> Staying at scene shows good faith emergency response, strengthens protection claim</li>
                <li><strong>Be Honest:</strong> Tell responders what happened. Don't lie about circumstances</li>
                <li><strong>Request Clarification:</strong> If police ask questions, you can ask about protections: "Am I protected under Good Samaritan law?"</li>
                <li><strong>Know Your Rights:</strong> You have right to remain silent beyond what law requires you to say</li>
                <li><strong>Document:</strong> Take note of who you spoke with, times, and what happened</li>
            </ul>
        </div>

        <div class="info-box warning">
            <strong>⚠️ Even Without Full Protection, Call</strong>
            <p>Do not hesitate to call 911 because of fear of legal consequences. A person's life is more important than any potential legal consequences. Call immediately. Police response prioritizes saving the life, not enforcement.</p>
        </div>
    </section>

    <!-- Myths & Facts -->
    <section class="samaritan-section">
        <h2>Myths vs. Facts About Good Samaritan Laws</h2>
        
        <div class="myth-busting">
            <div class="myth-card">
                <h4>❌ Myth: You'll Definitely Get Arrested</h4>
                <p><strong>Fact:</strong> Good Samaritan laws protect against arrest in overdose situations. While not 100% guaranteed, protection is real and enforcement is rare.</p>
            </div>

            <div class="myth-card">
                <h4>❌ Myth: Narcan Use Will Get You Arrested</h4>
                <p><strong>Fact:</strong> All 50 states protect naloxone administration during overdose. Even without a prescription, you're protected from liability.</p>
            </div>

            <div class="myth-card">
                <h4>❌ Myth: Immigration Status Matters</h4>
                <p><strong>Fact:</strong> Good Samaritan protections apply regardless of immigration status. Fear of deportation shouldn't stop you from calling 911.</p>
            </div>

            <div class="myth-card">
                <h4>❌ Myth: Paramedics Will Report You</h4>
                <p><strong>Fact:</strong> Paramedics' focus is saving life, not enforcement. Police presence is rare and they're informed of protections.</p>
            </div>

            <div class="myth-card">
                <h4>❌ Myth: Protection Only Works If You Leave</h4>
                <p><strong>Fact:</strong> Staying present and providing information STRENGTHENS your protection claim, shows good faith.</p>
            </div>

            <div class="myth-card">
                <h4>❌ Myth: Calling From Someone Else's Phone Helps</h4>
                <p><strong>Fact:</strong> Calling yourself provides better protection. Have someone else call only if you can't, but claim emergency yourself if possible.</p>
            </div>
        </div>
    </section>

    <!-- Special Circumstances -->
    <section class="samaritan-section">
        <h2>Special Circumstances</h2>
        
        <h3>Parole/Probation:</h3>
        <p>Good Samaritan laws provide limited protection if you're on parole or probation. While you won't face charges for the overdose call, substance use may be reported to your parole officer. That said, most parole officers recognize that preventing a death is more important than technical violations.</p>

        <h3>Drug Courts/Treatment:</h3>
        <p>If you're in drug court or treatment, calling 911 for overdose likely won't result in program termination. Courts and programs recognize the life-saving importance. However, discuss with your treatment provider if concerned.</p>

        <h3>Parents/Guardians:</h3>
        <p>Good Samaritan laws protect parents who call for their children. If your child overdoses, call 911 without fear. Laws specifically protect parent-child situations.</p>

        <h3>Undocumented Immigrants:</h3>
        <p>Immigration status doesn't affect Good Samaritan protections. Calling 911 for overdose won't trigger immigration enforcement. Life safety comes first.</p>
    </section>

    <!-- Resources -->
    <section class="samaritan-section">
        <h2>More Information & Resources</h2>
        
        <h3>Check Your Specific State's Laws:</h3>
        <ul>
            <li><strong>Harm Reduction Coalition:</strong> harmreduction.org - State-specific information</li>
            <li><strong>Network for Public Health Law:</strong> networkforphl.org - Legal resource library</li>
            <li><strong>SAMHSA:</strong> 1-800-662-4357 - Can provide information</li>
            <li><strong>State Attorney General's Office:</strong> Often has information on state laws</li>
        </ul>

        <h3>If You Face Legal Issues:</h3>
        <ul>
            <li>Contact a criminal defense attorney immediately</li>
            <li>Tell them about Good Samaritan law protections</li>
            <li>Many offer free consultations</li>
            <li>Some areas have legal aid societies for low-income help</li>
        </ul>

        <div class="info-box success">
            <strong>✓ Remember: Life First, Law Second</strong>
            <p>No legal consequence is worth a human life. If someone is overdosing, call 911 immediately. Good Samaritan protections are real and save lives. Don't hesitate.</p>
        </div>
    </section>

    <!-- Disclaimer -->
    <section class="samaritan-section disclaimer-section">
        <h2>⚠️ Legal Disclaimer</h2>
        <p>This information is educational and not legal advice. Laws vary by state and change over time. Consult with a qualified attorney in your state for specific legal questions.</p>
        <ul>
            <li>This is general information only, not legal advice</li>
            <li>Laws vary significantly by jurisdiction</li>
            <li>Consult attorney for your specific situation</li>
            <li>Good Samaritan laws continue to evolve</li>
            <li>In overdose emergency, always call 911 regardless of legal concerns</li>
        </ul>
        <p><strong>Your life matters. Saving a life matters. Call 911. The law protects you.</strong></p>
    </section>
</div>
@endsection
