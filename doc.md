# OLIGOWIZARD API Documentation

**Last updated: 2025-04-15**  
Version: 4.2.0

OLIGOWIZARD offers a powerful RESTful API designed to calculate and visualise properties of chemically modified oligonucleotides. This documentation provides an overview of the available API endpoints, accepted input parameters, and returned output fields.

All variable names in *italics* represent the keys used in the API request or response. Their expected types are indicated in square brackets (e.g., `[STRING]`, `[FLOAT]`, `[BOOL]`).

---

## Contents

- [Calculator Endpoint](#calculator-endpoint)
- [Custom Nucleotides](#custom-nucleotides)
- [Structure Drawing](#structure-drawing)
- [Vector Drawing](#vector-drawing)
- [Batch Calculator](#batch-calculator)
- [HPLC Toolbox](#hplc-toolbox)
- [Plasmid Map Drawing](#plasmid-map-drawing)
- [Available Terminal Modifications](#available-terminal-modifications)

---

## Calculator Endpoint (/advanced)

Performs biophysical and chemical calculations for a single oligonucleotide.

### Input Parameters

- *sequence* `[STRING]` — Required. Must be 5'→3'. Accepts single-letter codes for 2'-modifications and PO/PS backbone (uppercase = PO, lowercase = PS).
- *A260* `[FLOAT]` — Optional (default: `1.0`). Absorbance at 260 nm.
- *Na_conc* `[FLOAT]` — Optional (default: `50`). Sodium concentration in mM.
- *K_conc* `[FLOAT]` — Optional (default: `0`). Potassium concentration in mM.
- *Mg_conc* `[FLOAT]` — Optional (default: `0`). Magnesium concentration in mM.
- *three_prime* `[STRING|FLOAT]` — Optional (default: `"OH"`). 3'-end modification (or mass).
- *five_prime* `[STRING|FLOAT]` — Optional (default: `"OH"`). 5'-end modification (or mass).
- *five_is_PS* `[BOOL]` — Optional (default: `FALSE`). Was the 5'-modification attached via PS linkage?

### Output Fields

- *status* `[STRING]` — Success/failure flag.
- *sequence* `[STRING]` — Echoed input sequence.
- *sequence_sanitised* `[STRING]` — With custom bases replaced.
- *DNAeq* `[STRING]` — DNA-PO equivalent.
- *five_prime*, *three_prime* `[STRING]` — 5'/3' modification description.
- *A260*, *oligo_length*, *gc_cont*, *rev_comp* — Basic oligo stats.
- *tm*, *tm2* — Melting temperatures.
- *mass1* → canonical mass (DMT-OFF)  
- *mass2*, *mass3*, *mass4* → alternative protected states  
- *mass2_text*, *mass3_text*, *mass4_text* → explanation strings  
- *molext*, *molext_nn* — Extinction coefficients (simple & NN)  
- *conc1*, *conc2*, *conc1_nn*, *conc2_nn* — Calculated concentrations

---

## Custom Nucleotides

Define your own custom bases via the *custom_nt* parameter. Accepted fields per entry:

- *name* `[STRING]` — Display name.
- *mass* `[FLOAT]` — Monomer mass.
- *extinction* `[FLOAT]` — Extinction coefficient.
- *ACGT* `[CHAR]` — Closest DNA base (used for fallback).
- *tm* `[FLOAT]` — Approx. contribution to Tm.
- *colour* `[STRING]` — HEX colour code.

---

## Structure Drawing (/structure)

Generates `.cdxml` file of the oligo.

### Required Input

- *sequence* `[STRING]` — Using same notation as above.

### Optional Parameters

- *width*, *size*, *face* `[INTEGER]` — Bond/label appearance.
- *scale* `[FLOAT]` — Scale of entire drawing.

### Output

- *outfile* `[FILE]` — CDXML structure file.

---

## Vector Drawing (/draw)

Produces `.svg` vector drawing of the oligo and a legend.

### Required Input

- *sequence* `[STRING]` — Modified oligo input.
- *settings* `[ARRAY]` — Drawing settings (colours, stroke size, font size, etc).

### Optional

- *preset* `[STRING]` — Predefined appearance preset.

### Output

- *outfile1* — Oligo structure as SVG  
- *outfile2* — Corresponding legend as SVG

---

## Batch Calculator (! Currently not on the API !)

High-throughput version of the calculator. Expects a formatted CSV file as input.

### Required CSV Fields

- *name*, *sequence*, *five_prime*, *five_is_PS*, *three_prime*, *A260*

### Output

- *outfile* `[FILE]` — CSV with all relevant fields as in the calculator output.

---

## HPLC Toolbox (/HPLC)

Render HPLC traces and optionally run peak/purity analysis.

### Required

- *filename* `[FILE]` — Time/signal trace (CSV or TXT)

### Optional Inputs

Various appearance and analysis parameters, including:

- *title*, *canvasx*, *canvasy* `[STRING|INTEGER]`
- *xaxis_label*, *yaxis_label* `[STRING]`
- *limit*, *stroke*, *colour* `[FLOAT|STRING]`
- *analysis* `[STRING]` — "none", "peaks", or "purity"
- *sensitivity* `[INTEGER]` — Peak detection sensitivity

### Output

- *outfile* `[FILE]` — Annotated SVG trace

---

### Plasmid Map Drawing Tool (/plasmid)

A simple tool to draw minimalistic plasmid maps and return vector graphics.

---

### INPUT

- **Upload new file** (`filename`) `[FILE]`  
  *Required*  
  Data is entered by uploading a comma-separated text file.  
  Please use the provided [template](https://www.oligowizard.com/plasmid/plasmid-template.txt) to ensure compatibility.
**File format:**

- `Line 1`: **Plasmid Name** — `[STRING]`  
- `Line 2`: **Length in Base Pairs (BP)** — `[INTEGER]`  
- `Line 3+`: **Feature Name** `[STRING]`, **Start (BP)** `[INTEGER]`, **End (BP)** `[INTEGER]`, **Colour (HEX Code)** `[STRING]`

### OUTPUT

- **`plasmid_XXXXXX.svg`** (`outfile`) `[FILE]`  
Vector graphic file containing the plasmid map.  
Files can be viewed and edited in third-party vector graphic software (such as Adobe Illustrator or Inkscape).
---

## Available Terminal Modifications

List of supported modifications for *three_prime* and *five_prime*. Pass the string code as parameter.

### 3' Modifications

| Code | Name                        |
|------|-----------------------------|
| OH   | Hydroxy                     |
| P    | Monophosphate               |
| PPP  | Triphosphate                |
| FAM  | Fluorescein                 |
| CY3  | Cyanine 3                   |
| CY5  | Cyanine 5                   |
| BQ1  | Black Hole Quencher 1       |
| BQ2  | Black Hole Quencher 2       |
| 3N7  | 3'-Amino-Modifier C7        |
| 3N3  | 3'-PT-Amino-Modifier C3     |
| 3N6  | 3'-PT-Amino-Modifier C6     |
| 3S3  | 3'-Thiol-Modifier C3 S-S    |
| 3S6  | 3'-Thiol-Modifier 6 S-S     |

### 5' Modifications

| Code | Name                          |
|------|-------------------------------|
| OH   | Hydroxy                       |
| P    | Monophosphate                 |
| PPP  | Triphosphate                  |
| FAM  | Fluorescein                   |
| CY3  | Cyanine 3                     |
| CY5  | Cyanine 5                     |
| BQ1  | Black Hole Quencher 1         |
| BQ2  | Black Hole Quencher 2         |
| 5N5  | 5'-Amino-Modifier 5           |
| 5N6  | 5'-Amino-Modifier C6          |
| N12  | 5'-Amino-Modifier C12         |
| NTT  | 5'-Amino-Modifier TEG         |
| NTP  | 5'-Amino-Modifier TEG PDA     |
| NPD  | 5'-Amino-Modifier C12-PDA     |
| 5NO  | 5'-Aminooxy-Modifier-11       |
| 5C5  | 5'-Carboxy-Modifier C5        |
| C10  | 5'-Carboxy-Modifier C10       |
| 5SH  | 5'-Thiol-Modifier C6          |
| 5SS  | Thiol-Modifier C6 S-S         |
| HEX  | 5' Hexynyl                    |
| MAL  | Maleimide                     |
| CHL  | TEG-Cholesteryl               |

---

## Citation & Licence

All tools are free to use for academic and commercial purposes under Creative Commons. Please cite:

*“Molar Extinction Coefficients were calculated using the OLIGOWIZARD nucleic acid toolbox – available at https://www.oligowizard.com/.”*



