# OLIGOWIZARD API Documentation

**Last updated: 2025-04-17**  
Version: 1.0.0

OLIGOWIZARD offers a powerful RESTful API designed to calculate and visualise properties of chemically modified oligonucleotides. This documentation provides an overview of the available API endpoints, accepted input parameters, and returned output fields.

All variable names in *italics* represent the keys used in the API request or response. Their expected types are indicated in square brackets (e.g., `[STRING]`, `[FLOAT]`, `[BOOL]`).



---

## Contents

- [/advanced - Calculator Endpoint](##Oligo-Properties-Calculator-(/advanced))
- [/convert - Sequence Conversion Tool](##Sequence-Conversion-tool-(/convert))
- [/structure - Chemical Structure Generation](##Chemical-Structure-Generation-(/structure))
- [Available Terminal Modifications](##Available-Terminal-Modifications)
- [Citation](##Citation)


---

## Oligo Properties Calculator (/advanced)

Performs biophysical and chemical calculations for a single oligonucleotide.

### Input Parameters

- **Sequence** (*sequence*) `[STRING]` — required  
Sequences should be in 5' to 3' direction.
Oligowizard uses a dedicated code to represent the most commonly used 2' modification patterns:
DNA: (A / C / G / T), RNA: ( D / E / F / U), LNA: (H / I\* / J / K), MOE: (L / M\* / N / O), OMe: (P / Q / R / S), 2'- F:(V / W / X / Y)
Note here, that bases indicated with an asterisk carry an 5-Me on the nucleobase, as these are the most commonly used version of the phosphoramidite. Lower case letters are used to denote phosphorothioate linkages (PS) on the backbone, while capital letters symbolise regular phosphate diesters (PO). Placement of the PS linkage is relative to the 3' postion of the nucleotide. 
AC\*GT == AcGT. If the character at the 3' end of the sequence is in lower case, no changes are made to the result, as the 3' position does not usually carry a phosphate group. This distinction is however important, if a modification is attached to the 3' end!

- **3'-Modification** (*three_prime*) `[STRING|FLOAT]` — optional (default: `"OH"`)  
Modification attached at the three prime position of the oligonucleotide. If a string is passed, a lookup table for predefined modifications will be used to load mass, absorbance / melting properties, possible protecting groups / oxidation states.
If a floating point number is passed, mass calculations will use this (ommitting the 3'Oxygen) -- no further assumptions will be made about absorbance or melting properties. 
Linkage type (PO/PS) between the three prime nucleotide and possible modifcation will be calculated based on the capitalisation of the last character in the sequence.

- **5'-Modification** (*five_prime*) `[STRING|FLOAT]` — optional (default: `"OH"`)  
Modification attached at the five prime position of the oligonucleotide. If a string is passed, a lookup table for predefined modifications will be used to load mass, absorbance / melting properties, possible protecting groups / oxidation states.
If a floating point number is passed, mass calculations will use this (ommitting the 3'Oxygen) -- no further assumptions will be made about absorbance or melting properties.

- **5' Modification was attached via PS linkage** (*five_is_PS*) `[BOOL]` — optional (default: `FALSE`)  
Should be set to true if the 5' Modification linkage was sulfurised during synthesis to yield to correct molecular weight

- **Absorbance at 260 nm** (*A260*) `[FLOAT]` — optional (default: `1.0`)  
The measured absorbance (optical densitiy) at 260 nm. The value is used to calculate concentration, and melting temperature.

- **Sodium Concentration** (*Na_conc*) `[FLOAT]` — optional (default: `50`)  
The concentration of sodium cations (mM). Value is used in the melting temperature calculation

- **Potassium Concentration** (*K_conc*) `[FLOAT]` — optional (default: `0`)  
The concentration of potassium cations (mM). Value is used in the melting temperature calculation

- **Magnesium Concentration** (*Mg_conc*) `[FLOAT]` — optional (default: `0`)  
The concentration of magnesium cations (mM). Value is used in the melting temperature calculation



### Output Fields

- **Sequence** (*sequence*) `[STRING]`  
Returns the input sequence.

- **Custom NT removed** (*sequence_sanitised*) `[STRING]`  
Returns the input sequence with any custom nucleotides replaced by the closest mapping DNA base (based on user setting).

- **DNA-PO eq.** (*DNAeq*) `[STRING]`  
Returns the DNA-Phosphodiester equivalent of the input sequence: as capital letters (PO) with any non-DNA bases replaced by the corresponding DNA bases.

- **3' Modification** (*three_prime*) `[STRING]`  
Returns the input 3' modification mapped to its full name, or entered FLOAT value as string.

- **5' Modification** (*five_prime*) `[STRING]`  
Returns the input 5' modification mapped to its full name, or entered FLOAT value as string.

- **Absorbance at 260 nm** (*A260*) `[FLOAT]`  
Returns the input absorbance at 260 nanometer.

- **Length** (*oligo_length*) `[INTEGER]`  
Returns the length of the nucleotide in nucleotides (not counting terminal modifcations).

- **GC Content** (*gc_cont*) `[FLOAT]`  
Returns the percentage of Guanosine and Cytosin bases (or equivalents thereof) in the sequence.

- **Reverse Complement** (*rev_comp*) `[STRING]`  
Reverse complement of the sequence. Custom nucleotides will be replaced according to user-specified equivalent bases. 
Notably: as all characters will be inverted in capitalisation and backbone identity is relative to the 3' linkage, postion of PS linkages are not preserved correctly (AcGGT-> ACCgT == A-C\*G-G-T -> A-C-C-G\*T) this is a known bug.

- **DNA melting temperature** (*tm1*) `[FLOAT]`  
Melting temperature, assuming the oligonucleotide is a DNA PO strand (1, 2, 3)

- **Melting temperature approximation** (*tm2*) `[STRING]`  
Estimated melting temperature with correction values applied for sugar modifications where available (currently, MOE and LNA).
Returned as a string with a range.
Takes user-specified modifiers into account.

- **Molecular weight (canonical)** (*mass1*) `[FLOAT]`  
Molecular weight of the oligo and terminal modifications with all protecting groups removed (DMT-OFF)

- **Molecular weight - Alternative 3' Mass** (*mass2*) `[FLOAT]` — (default: `0`)  
If the modification at the 3' position of the oligo has an alternative mass depending on protection groups / oxidative state, the corresponding mass is returned here.

- **Alternative 3' Mass Identity** (*mass2_text*) `[STRING]`  
Short explanation, which alternative form of the 3' modification was taken into account for the alternative mass.

- **Molecular weight - Alternative 5' Mass** (*mass3*) `[FLOAT]`  
If the modification at the 5' position of the oligo has an alternative mass depending on protection groups / oxidative state, the corresponding mass is returned here.
If no modification was specified, the DMT-ON weight will be given here.

- **Alternative 5' Mass Identity** (*mass3_text*) `[STRING]`  
Short explanation, which alternative form of the 5' modification was taken into account for the alternative mass.
For unmodified oligos, this will be "5' DMT protected"

- **Molecular weight - Double modified** (*mass4*) `[FLOAT]`  
If the modification at the 3' and 5' positions of the oligo have an alternative mass depending on protection groups / oxidative state, the corresponding mass is returned here.
If no modification was specified for the 5' end, the DMT-ON weight will be given here in combination with the 3' alternative mass.

- **Alternative Mass Identity** (*mass4_text*) `[STRING]`  
Short explanation, which alternative forms of the 3' and 5' modification was taken into account for the alternative mass.

- **Molar Extinction Coefficient (simple)** (*molext*) `[FLOAT]`  
Molar extinction coefficient (in L mol-1 cm-1) of the oligo and any present modifcations based on a simple extinction model.
Will take user specified extinction values into account

- **Concentration (Simple extinction)** (*conc1*) `[FLOAT]`  
Concentration of the oligo (in micromole per liter) based on simple extinction coefficient and specified absorbance at 260 nm (A260).

- **Concentration (Simple extinction)** (*conc2*) `[FLOAT]`  
Concentration of the oligo (in nanogram per microliter) based on simple extinction coefficient and specified absorbance at 260 nm (A260).

- **Molar Extinction Coefficient (NN Model)** (*molext_nn*) `[FLOAT]`  
Molar extinction coefficient (in L mol-1 cm-1) of the oligo based on the nearast neighbor model.
Currently only takes nucleobases into account.

- **Concentration (NN Model)** (*conc1_nn*) `[FLOAT]`  
Concentration of the oligo (in micromole per liter) based on the nearast neighbor model extinction coefficient and specified absorbance at 260 nm (A260).

- **Concentration (NN Model)** (*conc2_nn*) `[FLOAT]`  
Concentration of the oligo (in nanogram per microliter)  based on the nearast neighbor model extinction coefficient and specified absorbance at 260 nm (A260).

---

## Sequence Conversion tool (/convert)

This tool provides a search-and-replace function to convert your sequence in- and out- of oligowizard code

### Input Parameters

- **Sequence** (*sequence*) `[STRING]` — required  
Sequences should be in 5' to 3' direction.
Oligowizard uses a dedicated code to represent the most commonly used 2' modification patterns:
DNA: (A / C / G / T), RNA: ( D / E / F / U), LNA: (H / I\* / J / K), MOE: (L / M\* / N / O), OMe: (P / Q / R / S), 2'F: (V / W / X / Y)
Note here, that bases indicated with an asterisk carry an 5-Me on the nucleobase, as these are the most commonly used version of the phosphoramidite. Lower case letters are used to denote phosphorothioate linkages (PS) on the backbone, while capital letters symbolise regular phosphate diesters (PO).

- **Input Code** (*input_code*) `[STRING]` — required  
Defines which nucleotides should be replaced - only those nucleotides will be affected!  
`DNA` , `RNA` , `LNA` ,  `MOE` ,  `OMe` , `2'F` 

- **Input Code** (*output_code*) `[STRING]` — required  
Sets which code set should be used for the replacement  
`DNA` , `RNA` , `LNA` ,  `MOE` ,  `OMe` , `2'F` 


### Output Fields

- **Sequence** (*sequence*) `[STRING]`  
Returns the given sequence

- **Input Code** (*input_code*) `[STRING]`  
Returns the given code to be converted from

- **Input Code** (*output_code*) `[STRING]`  
Returns the given code to be converted to

- **Converted Sequence** (*output*) `[STRING]`  
Returns the sequence with selected nucleotides replaced.

---

## Chemical Structure Generation (/structure)

This tool allows you to generate structure files (as *.cdxml) from a given nucleotide sequence. These files can be viewed and edited in a compatible third-party structure editor such as ChemDraw or ACD/ChemSketch.

***DISCLAIMER** ‘ChemDraw’ is a registered property of Revvity, Inc. (formerly PerkinElmer, Inc.), and ‘ACD/ChemSketch’ is a registered property of Advanced Chemistry Development, Inc. (ACD/Labs). OLIGOWIZARD LTD has no affiliation with Revvity, Inc. or ACD/Labs. The chemical structure drawing feature generates CDXML files compatible with software like ChemDraw (a separate licence may be required) and similar tools, including ACD/ChemSketch. OLIGOWIZARD LTD does not provide software licences or support for these third-party applications.*

### Input Parameters

- **Sequence** (*sequence*) `[STRING]` — required  
Sequences should be in 5' to 3' direction.
Oligowizard uses a dedicated code to represent the most commonly used 2' modification patterns:
DNA: (A / C / G / T), RNA: ( D / E / F / U), LNA: (H / I\* / J / K), MOE: (L / M\* / N / O), OMe: (P / Q / R / S), 2'- F:(V / W / X / Y)
Note here, that bases indicated with an asterisk carry an 5-Me on the nucleobase, as these are the most commonly used version of the phosphoramidite. Lower case letters are used to denote phosphorothioate linkages (PS) on the backbone, while capital letters symbolise regular phosphate diesters (PO). Placement of the PS linkage is relative to the 3' postion of the nucleotide. 
AC\*GT == AcGT. If the character at the 3' end of the sequence is in lower case, no changes are made to the result, as the 3' position does not usually carry a phosphate group. This distinction is however important, if a modification is attached to the 3' end!

- **Download filename and directory** (*filename*) `[STRING]` — optional (default: `NONE`)  
Sets the directory/filname for the outfile. 
Defaults to the current directory with a random, URL-safe filename:  
`structure_XXXXXX.cdxml`

- **Bond Line Width** (*width*) `[INTEGER]` — optional (default: `1`)  
Sets the width of the bonds.

- **Atom Label Font Size** (*size*) `[INTEGER]` — optional (default: `12`)  
Sets the font size for atom labels.

- **LabelFace** (*face*) `[INTEGER]` — optional (default: `96`)  
Determines the appearnces of atom labels - default/bold. Enter 97 for bold

- **Scaling factor** (*scale*) `[FLOAT]` — optional (default: `0.45`)  
Scales the size of the molecule. Especially useful to fit larger molecules on one page.

### Output
- **Structure file** (*target_path*) `[STRING]`  
Returns the filepath of the created *.cdxml file.
Defaults to the current directory with a random, URL-safe filename:  
`structure_XXXXXX.cdxml`

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

## Citation

We kindly ask you to cite:
*“Oligo properties were calculated using the OLIGOWIZARD nucleic acid toolbox – available at https://www.oligowizard.com/.”*