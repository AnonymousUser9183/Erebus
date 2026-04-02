@extends('layouts.auth')

@section('title', 'Marquis Reagent Test Guide - Harm Reduction')

@section('breadcrumb', 'Marquis Reagent Test')

@section('content')
<style>
    .marquis-test-page {
        max-width: 1000px;
        margin: 0 auto;
        padding: 0;
    }

    .marquis-header {
        background: linear-gradient(135deg, #0d5b7c 0%, #1a7a99 100%);
        color: white;
        padding: 32px 24px;
        border-bottom: 3px solid #2a9db8;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px 8px 0 0;
    }

    .marquis-header h1 {
        font-size: 32px;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .marquis-header p {
        font-size: 16px;
        opacity: 0.9;
        margin: 0;
    }

    .marquis-section {
        background-color: #ffffff;
        border-radius: 8px;
        padding: 32px;
        margin-bottom: 32px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        border: 1px solid #e0e0e0;
    }

    .marquis-section h2 {
        color: #0d5b7c;
        font-size: 24px;
        margin-bottom: 16px;
        padding-bottom: 16px;
        border-bottom: 2px solid #2a9db8;
    }

    .marquis-section h3 {
        color: #1a7a99;
        font-size: 18px;
        margin-top: 16px;
        margin-bottom: 12px;
        font-weight: 600;
    }

    .marquis-section p {
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

    .marquis-section ul,
    .marquis-section ol {
        margin-left: 16px;
        margin-bottom: 16px;
    }

    .marquis-section li {
        margin-bottom: 12px;
        color: #666666;
        line-height: 1.7;
    }

    .steps-container {
        display: grid;
        gap: 16px;
        margin-bottom: 16px;
    }

    .step {
        display: flex;
        gap: 16px;
        padding: 16px;
        background-color: #f5f5f5;
        border-radius: 6px;
        border: 1px solid #e0e0e0;
    }

    .step-number {
        background: linear-gradient(135deg, #1a7a99 0%, #2a9db8 100%);
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        flex-shrink: 0;
        font-size: 18px;
    }

    .step-content h4 {
        color: #0d5b7c;
        font-size: 16px;
        margin-bottom: 8px;
        font-weight: 600;
        margin: 0 0 8px 0;
    }

    .step-content p {
        margin: 0;
        font-size: 14px;
        color: #666666;
    }

    .color-reaction-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 16px;
    }

    .color-reaction-table th {
        background-color: #0d5b7c;
        color: white;
        padding: 16px;
        text-align: left;
        font-weight: 600;
    }

    .color-reaction-table td {
        padding: 16px;
        border-bottom: 1px solid #e0e0e0;
    }

    .color-reaction-table tr:hover {
        background-color: #f5f5f5;
    }

    .color-swatch {
        display: inline-block;
        width: 30px;
        height: 30px;
        border-radius: 4px;
        border: 1px solid #999;
        margin-right: 8px;
        vertical-align: middle;
    }

    .safety-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 16px;
        margin-bottom: 16px;
    }

    .safety-card {
        background-color: #f5f5f5;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        padding: 16px;
    }

    .safety-card h4 {
        color: #0d5b7c;
        font-size: 16px;
        margin: 0 0 12px 0;
        font-weight: 600;
    }

    .safety-card p {
        margin: 0;
        font-size: 14px;
        color: #666666;
        line-height: 1.6;
    }

    .comparison-section {
        background-color: #f0f9fb;
        border-radius: 6px;
        padding: 20px;
        margin-bottom: 16px;
    }

    .comparison-section h4 {
        color: #0d5b7c;
        font-size: 16px;
        margin-bottom: 12px;
        font-weight: 600;
    }

    .comparison-section ul {
        margin-left: 16px;
        margin-bottom: 0;
    }

    .disclaimer-section {
        border: 2px solid #dc2626;
        background-color: #fee2e2;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .marquis-header h1 {
            font-size: 24px;
        }

        .marquis-header p {
            font-size: 14px;
        }

        .marquis-section {
            padding: 16px;
        }

        .marquis-section h2 {
            font-size: 20px;
        }

        .step {
            flex-direction: column;
            gap: 12px;
        }

        .color-reaction-table {
            font-size: 13px;
        }

        .color-reaction-table th,
        .color-reaction-table td {
            padding: 12px;
        }

        .safety-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 480px) {
        .marquis-header h1 {
            font-size: 20px;
        }

        .marquis-section {
            padding: 12px;
        }

        .marquis-section h2 {
            font-size: 18px;
        }

        .marquis-section h3 {
            font-size: 16px;
        }

        .info-box {
            padding: 12px;
        }

        .color-swatch {
            width: 25px;
            height: 25px;
        }
    }
</style>

<div class="marquis-test-page">
    <!-- Header -->
    <div class="marquis-header">
        <h1>🧬 Marquis Reagent Test Guide</h1>
        <p>General substance identification test. Color-changing reaction indicates substance type.</p>
    </div>

    <!-- What Is Marquis Reagent? -->
    <section class="marquis-section">
        <h2>What Is the Marquis Reagent Test?</h2>
        <p>The Marquis Reagent Test is a simple chemical test that produces a color-changing reaction when mixed with various substances. This color reaction helps identify what substance is present. It's one of the most popular harm reduction testing tools due to its simplicity, low cost, and rapid results.</p>
        
        <div class="info-box success">
            <strong>✓ Why It's Useful</strong>
            <p>The Marquis test provides quick visual identification of many common substances. A different color reaction indicates a different substance, helping users make informed decisions about what they're consuming.</p>
        </div>

        <h3>Key Features</h3>
        <ul>
            <li><strong>Rapid Results:</strong> Color reaction appears within seconds to 1 minute</li>
            <li><strong>Affordable:</strong> Reagent bottles are inexpensive and widely available</li>
            <li><strong>Easy to Use:</strong> Minimal equipment needed - just a small sample and the reagent</li>
            <li><strong>Portable:</strong> Small bottle fits easily in a bag or pocket</li>
            <li><strong>Distinctive Colors:</strong> Different substances produce markedly different color reactions</li>
            <li><strong>Long Shelf Life:</strong> Properly stored reagent lasts for years</li>
        </ul>
    </section>

    <!-- How to Use -->
    <section class="marquis-section">
        <h2>How to Perform a Marquis Test</h2>
        
        <div class="steps-container">
            <div class="step">
                <div class="step-number">1</div>
                <div class="step-content">
                    <h4>Prepare Your Sample</h4>
                    <p>Place a tiny amount of substance (approximately the size of a grain of rice or a few milligrams) on a white ceramic plate, porcelain tile, or white paper. For powdered substances, scrape a small amount to the surface.</p>
                </div>
            </div>

            <div class="step">
                <div class="step-number">2</div>
                <div class="step-content">
                    <h4>Apply the Reagent</h4>
                    <p>Add 1-2 drops of Marquis reagent directly to the sample. Do not mix or stir. The reaction will begin immediately upon contact. Keep the bottle cap closed between drops to prevent evaporation and chemical degradation.</p>
                </div>
            </div>

            <div class="step">
                <div class="step-number">3</div>
                <div class="step-content">
                    <h4>Observe the Color Change</h4>
                    <p>Watch the color reaction carefully over the next 30-60 seconds. Note the initial color and any color changes that occur. The final color is what you use for identification. Some reactions happen instantly; others develop over several seconds.</p>
                </div>
            </div>

            <div class="step">
                <div class="step-number">4</div>
                <div class="step-content">
                    <h4>Compare to Reference Chart</h4>
                    <p>Compare the observed color to a Marquis color identification chart. Cross-reference the color with the substance list. Remember that purity, age, and chemical composition of substances can slightly affect color intensity but not the basic color category.</p>
                </div>
            </div>
        </div>

        <div class="info-box warning">
            <strong>⚠️ Important Notes</strong>
            <p>Color matching is subjective and can be affected by lighting conditions. Take the test in natural daylight or bright LED lighting for most accurate results. If uncertain about results, use complementary tests (Mecke, Mandelin, Simon's) for confirmation.</p>
        </div>
    </section>

    <!-- Color Reactions Chart -->
    <section class="marquis-section">
        <h2>Marquis Color Reaction Chart</h2>
        <p>Below is a reference guide for common substance reactions. Note that this is not exhaustive, and reactions can vary based on purity and composition.</p>
        
        <table class="color-reaction-table">
            <thead>
                <tr>
                    <th>Substance</th>
                    <th>Marquis Reaction</th>
                    <th>Reaction Timeline</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>MDMA / Ecstasy</strong></td>
                    <td><span class="color-swatch" style="background-color: #4a0000;"></span> Black → Dark Purple/Brown</td>
                    <td>Immediate to 5 seconds</td>
                </tr>
                <tr>
                    <td><strong>MDA (Tenamfetamine)</strong></td>
                    <td><span class="color-swatch" style="background-color: #4a2020;"></span> Black → Dark Brown</td>
                    <td>Immediate to 10 seconds</td>
                </tr>
                <tr>
                    <td><strong>Amphetamine</strong></td>
                    <td><span class="color-swatch" style="background-color: #ff9900;"></span> Orange → Brown/Black</td>
                    <td>2-5 seconds</td>
                </tr>
                <tr>
                    <td><strong>Methamphetamine</strong></td>
                    <td><span class="color-swatch" style="background-color: #ffcc00;"></span> Yellow → Brown</td>
                    <td>3-10 seconds</td>
                </tr>
                <tr>
                    <td><strong>Heroin</strong></td>
                    <td><span class="color-swatch" style="background-color: #ff6b9d;"></span> Brown → Purple/Blue</td>
                    <td>3-10 seconds</td>
                </tr>
                <tr>
                    <td><strong>Morphine</strong></td>
                    <td><span class="color-swatch" style="background-color: #cccccc;"></span> White → Brown</td>
                    <td>5-15 seconds</td>
                </tr>
                <tr>
                    <td><strong>Mescaline (Peyote/Cacti)</strong></td>
                    <td><span class="color-swatch" style="background-color: #ff3333;"></span> Red → Brown</td>
                    <td>1-2 seconds</td>
                </tr>
                <tr>
                    <td><strong>DOB (Bromo-Dragonfly)</strong></td>
                    <td><span class="color-swatch" style="background-color: #00aa00;"></span> Green → Yellow → Brown</td>
                    <td>1-3 seconds</td>
                </tr>
                <tr>
                    <td><strong>Fentanyl</strong></td>
                    <td><span class="color-swatch" style="background-color: #ffff99;"></span> Pale Yellow → Orange</td>
                    <td>Slow, develops over 10-30 seconds</td>
                </tr>
                <tr>
                    <td><strong>LSD / Acid</strong></td>
                    <td><span class="color-swatch" style="background-color: #ffcc99;"></span> Orange/Tan → Brown</td>
                    <td>5-10 seconds</td>
                </tr>
                <tr>
                    <td><strong>No Reaction</strong></td>
                    <td>No color change or very faint yellow</td>
                    <td>Substance not detected or inert</td>
                </tr>
            </tbody>
        </table>

        <div class="info-box">
            <strong>💡 Note on Color Interpretation</strong>
            <p>Lighting conditions significantly affect how colors appear. Test in natural daylight or bright white LED lighting. Take a photo if possible to compare later or with reference guides. Color intensity depends on purity - lower purity may produce fainter reactions.</p>
        </div>
    </section>

    <!-- Advantages & Limitations -->
    <section class="marquis-section">
        <h2>Advantages and Limitations</h2>
        
        <div class="safety-grid">
            <div class="safety-card">
                <h4>✓ Advantages</h4>
                <ul style="margin-left: 0; padding-left: 20px;">
                    <li>Very fast results</li>
                    <li>Inexpensive per test</li>
                    <li>Easy to carry and use</li>
                    <li>Distinctive color reactions</li>
                    <li>Long shelf life</li>
                    <li>Works on most substances</li>
                </ul>
            </div>
            <div class="safety-card">
                <h4>✗ Limitations</h4>
                <ul style="margin-left: 0; padding-left: 20px;">
                    <li>Color matching is subjective</li>
                    <li>Cannot determine purity</li>
                    <li>Cannot detect all substances</li>
                    <li>Cannot identify analogues precisely</li>
                    <li>Chemical spill hazard</li>
                    <li>Affected by lighting conditions</li>
                </ul>
            </div>
        </div>

        <div class="info-box danger">
            <strong>🚨 Critical Information</strong>
            <p>The Marquis test is a PRESUMPTIVE test only. A positive result suggests the presence of a substance but does not confirm 100% certainty. Similar substances can produce similar colors. Always use multiple tests for confirmation when possible.</p>
        </div>
    </section>

    <!-- Proper Handling & Safety -->
    <section class="marquis-section">
        <h2>Safe Handling of Marquis Reagent</h2>
        
        <div class="comparison-section">
            <h4>⚠️ Safety Precautions</h4>
            <ul>
                <li>Store in a cool, dark place away from sunlight</li>
                <li>Keep away from children and pets</li>
                <li>Do not inhale fumes</li>
                <li>Use in a well-ventilated area</li>
                <li>Avoid skin contact - if exposed, wash thoroughly with water</li>
                <li>Never ingest or allow to contact eyes</li>
                <li>Check expiration date before use (typically 1-3 years)</li>
                <li>Dispose of properly according to local regulations</li>
            </ul>
        </div>

        <div class="comparison-section">
            <h4>🧪 Storage Requirements</h4>
            <ul>
                <li>Store in original amber/dark glass bottle with tight cap</li>
                <li>Keep in a cool environment (15-25°C / 59-77°F preferred)</li>
                <li>Protect from light exposure - light degrades reagents</li>
                <li>Keep away from moisture and humidity</li>
                <li>Store separately from other chemicals to prevent cross-contamination</li>
                <li>Use only glass or ceramic surfaces for testing - plastic can react with reagent</li>
            </ul>
        </div>
    </section>

    <!-- Complementary Tests -->
    <section class="marquis-section">
        <h2>Using Marquis With Other Tests</h2>
        <p>For more reliable identification, use Marquis alongside complementary reagent tests:</p>
        
        <div class="safety-grid">
            <div class="safety-card">
                <h4>Mecke Reagent</h4>
                <p>Produces different color reactions. Useful for confirming MDMA/MDA or identifying opioids that may show similar colors to Marquis.</p>
            </div>
            <div class="safety-card">
                <h4>Mandelin Reagent</h4>
                <p>Very sensitive to many substances. Good for distinguishing between similar compounds like MDMA, MDA, and other amphetamines.</p>
            </div>
            <div class="safety-card">
                <h4>Simon's Reagent</h4>
                <p>Specifically tests for secondary amines. Helps confirm the presence of amphetamines and MDMA when used alongside Marquis.</p>
            </div>
        </div>

        <div class="info-box success">
            <strong>✓ Best Practice</strong>
            <p>Use a TEST KIT with multiple reagents (Marquis, Mecke, Mandelin) together for greater accuracy. Most kits include all three or more reagents for this purpose. Combining tests significantly increases reliability of identification.</p>
        </div>
    </section>

    <!-- Harm Reduction Context -->
    <section class="marquis-section">
        <h2>Harm Reduction Context</h2>
        <p>Test kits are one part of a comprehensive harm reduction strategy:</p>
        
        <h3>Before Testing:</h3>
        <ul>
            <li>Obtain substance from trusted source when possible</li>
            <li>Test in a safe, private location</li>
            <li>Have a trusted friend aware of your testing</li>
            <li>Keep naloxone (Narcan) available nearby</li>
        </ul>

        <h3>After Getting Results:</h3>
        <ul>
            <li>If positive result: Reconsider use or significantly reduce amount</li>
            <li>If negative result: Lower risk but not risk-free - other compounds may be present</li>
            <li>If uncertain: Discard substance or test with additional reagents</li>
            <li>Never use alone</li>
            <li>Start with extremely small test dose</li>
            <li>Have medical help available (phone ready, trusted friend nearby)</li>
        </ul>

        <h3>Important Principles:</h3>
        <ul>
            <li>Testing reduces but does not eliminate risk</li>
            <li>Even pure substances carry health risks</li>
            <li>Seek professional help if struggling with substance use</li>
            <li>Recovery resources and treatment are available</li>
        </ul>
    </section>

    <!-- Resources and Support -->
    <section class="marquis-section">
        <h2>Additional Resources</h2>
        
        <h3>Where to Get Test Kits:</h3>
        <ul>
            <li><strong>Online:</strong> Harm reduction websites and suppliers (legal in many areas)</li>
            <li><strong>Local Programs:</strong> Harm reduction organizations in your area</li>
            <li><strong>Universities:</strong> Many colleges have drug checking services</li>
            <li><strong>Health Departments:</strong> Some provide free test kits</li>
        </ul>

        <h3>Emergency Numbers:</h3>
        <ul>
            <li><strong>911:</strong> Emergency services (overdose, medical emergency)</li>
            <li><strong>988:</strong> Suicide & Crisis Lifeline</li>
            <li><strong>1-800-662-4357:</strong> SAMHSA National Helpline (treatment and referral)</li>
        </ul>

        <h3>Further Information:</h3>
        <ul>
            <li>DrugsData.org - Independent substance testing and analysis</li>
            <li>Harm Reduction Coalition - harmreduction.org</li>
            <li>MAPS (Multidisciplinary Association for Psychedelic Studies) - Safety information</li>
            <li>Local harm reduction organizations in your area</li>
        </ul>

        <div class="info-box">
            <strong>💡 Remember</strong>
            <p>Testing is a harm reduction tool, not permission to use substances. If you or someone you know is struggling with substance use, help is available. Treatment works, and recovery is possible.</p>
        </div>
    </section>

    <!-- Disclaimer -->
    <section class="marquis-section disclaimer-section">
        <h2>⚠️ Important Disclaimer</h2>
        <p>This information is provided for harm reduction and educational purposes only. It does not constitute medical, legal, or professional advice.</p>
        <ul>
            <li>Test kits are harm reduction tools, not endorsements of substance use</li>
            <li>Results are presumptive and may require confirmation</li>
            <li>Substance use carries inherent risks that cannot be eliminated</li>
            <li>Always consult healthcare providers for medical advice</li>
            <li>Substance possession may be illegal in your jurisdiction</li>
            <li>If struggling with substance use, please seek professional help</li>
        </ul>
        <p><strong>Recovery is possible. Help is available. You are not alone.</strong></p>
    </section>
</div>
@endsection
