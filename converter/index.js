codemap = [
  { in: "*ZEROS", out: "0" },
  { in: "*BLANKS", out: "''" },
  { in: "*ON", out: "false" },
  { in: "*OFF", out: "true" },
  { in: "[#|$|*]", out: "_" },
];
String.prototype.codify = function (inputLanguage, outputLanguage) {
  let str = this;
  codemap.forEach(mapping => {
      let target = mapping.in.endsWith(']')?new RegExp(mapping.in,"g"):mapping.in;
      str = str.replace(target,mapping.out);
  });
  return str;
}
varmap = [
  { in: "^(_IN90)", out: "billpay" },
  { in: "^(BPREC)", out: "billpay" },
  { in: "^(BP)", out: "billpay->BP" },
  { in: "^(_IN91)", out: "customer" },
  { in: "^(CUREC)", out: "customer" },
  { in: "^(CU)", out: "customer->CU" },
  { in: "^(_IN92)", out: "aropen" },
  { in: "^(_IN95)", out: "aropen" },
  { in: "^(AROREC)", out: "aropen" },
  { in: "^(ARO)", out: "aropen->ARO" },
  { in: "^(_IN77)", out: "acctgper" },
  { in: "^(ATGREC)", out: "acctgper" },
  { in: "^(ATG)", out: "acctgper->ATG" },
  { in: "^(CRREC)", out: "receipts" },
  { in: "^(CR)", out: "receipts->CR" },
];
String.prototype.varify = function (inputLanguage, outputLanguage) {
  if (this === 'false' || this === 'true' || this.startsWith("'")|| this.startsWith("-") || !isNaN(Number(this.substr(0, 1)))){
    return this; // number, string or keyword
  }
  else {
    let str = this;
    varmap.forEach(r => {
      let target = r.in.endsWith(')')?new RegExp(r.in):r.in;
      str = str.replace(target,r.out);
    });
    return `$${str}`;
  }
}
class CodeLine {
  constructor(_raw) {
    this.raw = _raw;
    this.cmd = _raw.substr(35, 5).trim(); // command
    this.f1 = _raw.substr(25, 10).trim().codify(); // factor 1
    this.f2 = _raw.substr(40, 10).trim().codify(); // factor 2
    this.result = _raw.substr(50, 8).trim().codify(); // result
    this.i1 = "_IN" + _raw.substr(58, 9).trim(); // indicator 1 (index)
    this.i2 = _raw.substr(67, 20).trim(); // indicator 2
    this.id = _raw.substr(0, 7).trim(); // indicator 2
  }

  get type() {
    if (this.cmd.startsWith('KL'))
      return 'view';
    else if (this.cmd.startsWith('KF'))
      return 'view field';
    else if (this.raw.substr(16, 2) === '--')
      return 'header';
    else if (this.raw.substr(14, 1) === '*')
      return 'comment';
    else if (this.raw.substr(13, 1) === 'C')
      return 'code';
    return 'other';
  }

  get comment() {
    switch (this.cmd.toLowerCase()) {
      case 'endif':
        return `/* #${this.raw.substr(0, 5).trim()} end if */`;
      case 'enddo':
        return `/* #${this.raw.substr(0, 5).trim()} end while */`;
      case 'endsr':
        return `/* #${this.raw.substr(0, 5).trim()} end function */`;
      case 'write':
        return `/* #${this.raw.substr(0, 5).trim()} insert record */`;
      case 'updat':
        return `/* #${this.raw.substr(0, 5).trim()} update record */`;
      default:
        return `/* #${this.raw.substr(0, 5).trim()} */`;
    }
  }

  get converted() {
    switch (this.cmd.toLowerCase()) {
      case 'klist': // define field array, end after last kfld
      case 'kfld': // define field in field array
      case 'setll': // set cursor at first record
      case 'endsr': // end subroutine
      case 'enddo': // end while
      case 'endif': // end if
      case 'excpt': // allow records to be written
        return;
      case "movel":
      case "move":
      case "z-add":
        return `${this.result.varify()} = ${this.f2.varify()};${this.comment}`;
      case "add":
        return `${this.result.varify()} += ${this.f2.varify()};${this.comment}`;
      case "z-sub":
        return `${this.result.varify()} = -(${this.f2.varify()});${this.comment}`;
      case "sub":
        if (this.f1) {
          return `${this.result.varify()} = ${this.f1.varify()} - ${this.f2.varify()};${this.comment}`;
        }
        return `${this.result.varify()} -= ${this.f2.varify()};${this.comment}`;
      case "mult":
      if (this.f1) {
        return `${this.result.varify()} = ${this.f1.varify()} * ${this.f2.varify()};${this.comment}`;
      }
      return `${this.result.varify()} *= ${this.f2.varify()};${this.comment}`;
      case "doueq":
        return `while (${this.f1.varify()} !== ${this.f2.varify()}`;
      case "doweq":
        return `while (${this.f1.varify()} === ${this.f2.varify()}`;
      case "ifeq":
        if (this.f2 === 'true')
          return `if (${this.f1.varify()}`;
        else if (this.f2 === 'false')
          return `if (!${this.f1.varify()}`;
        return `if (${this.f1.varify()} === ${this.f2.varify()}`;
      case "ifne":
        return `if (${this.f1.varify()} !== ${this.f2.varify()}`;
      case "ifgt":
        return `if (${this.f1.varify()} > ${this.f2.varify()}`;
      case "ifge":
        return `if (${this.f1.varify()} >= ${this.f2.varify()}`;
      case "iflt":
        return `if (${this.f1.varify()} < ${this.f2.varify()}`;
      case "ifle":
        return `if (${this.f1.varify()} <= ${this.f2.varify()}`;
      case "andeq":
        if (this.f2 === 'true')
          return `&& ${this.f1.varify()}`;
        else if (this.f2 === 'false')
          return `&& !${this.f1.varify()}`;
        return `&& ${this.f1.varify()} === ${this.f2.varify()}`;
      case "andne":
        return `&& ${this.f1.varify()} !== ${this.f2.varify()}`;
      case "andgt":
        return `&& ${this.f1.varify()} > ${this.f2.varify()}`;
      case "andge":
        return `&& ${this.f1.varify()} >= ${this.f2.varify()}`;
      case "andlt":
        return `&& ${this.f1.varify()} < ${this.f2.varify()}`;
      case "andle":
        return `&& ${this.f1.varify()} <= ${this.f2.varify()}`;
      case "oreq":
        if (this.f2 === 'true')
          return `|| ${this.f1.varify()}`;
        else if (this.f2 === 'false')
          return `|| !${this.f1.varify()}`;
        return `|| ${this.f1.varify()} === ${this.f2.varify()}`;
      case "orne":
        return `|| ${this.f1.varify()} !== ${this.f2.varify()}`;
      case "orgt":
        return `|| ${this.f1.varify()} > ${this.f2.varify()}`;
      case "orge":
        return `|| ${this.f1.varify()} >= ${this.f2.varify()}`;
      case "orlt":
        return `|| ${this.f1.varify()} < ${this.f2.varify()}`;
      case "orle":
        return `|| ${this.f1.varify()} <= ${this.f2.varify()}`;
      case "exsr":
        return `$${this.f2}();${this.comment}`;
      case "begsr":
        return `function ${this.f1}() {`;
      case "else":
        return `} else {`;
      case 'chain': // fetch one row into variable
      case "reade": // fetch all rows
        return `${this.i1.varify()}_rows = db::fetchRows($sql, 'DTSDATA.${this.f2}');${this.comment}`;
      case "write": // table insert
        return `${this.f2.varify()}->insert();${this.comment}`;
      case "updat": // table update
        return `${this.f2.varify()}->update();${this.comment}`;
      case "time": return `${this.result.varify()} = time();`;
      default:
        return (this.result ? this.result.varify() + " = " : "") + this.cmd + "('" + this.f1 + "','" + this.f2 + "','" + this.i1 + "','" + this.i2 + "');";
    }
  }
}

class CodeBlock {
  constructor(Lines) {
    this.lines = Lines;
    this.row = '';
    this.condition = false;
  }

  get converted() {
    let linecount = this.lines.length;
    let self = this;
    let out = [];
    for (let i = 0; i < linecount; i++) {
      if (self.lines.length < 1) return out; // finished!
      let codeLine = new CodeLine(self.lines[0]);
      self.lines.shift();
      if (codeLine && codeLine.type === 'code' && !codeLine.cmd.startsWith('K') && !codeLine.cmd.startsWith('SET')) {
        switch (codeLine.cmd.substr(0, 2)) {
          case 'AN':
          case 'OR':
            out.push(codeLine.converted);
            break;
          case 'EN':
            out.push('}' + codeLine.comment);
            return out; // return if, while, or function block
          case 'IF':
          case 'DO':
          case 'BE':
            if (self.condition) {
              out.push(") {\n" + codeLine.converted); // terminate if, while
              self.condition = false;
            } else {
              out.push(codeLine.converted);
            }
            let child = new CodeBlock(self.lines);
            child.condition = !codeLine.cmd.startsWith('BE'); // terminate if, while
            let child_out = child.converted;
            if (child_out.length > 0)
              out.push(child_out.join("\n"));
            self.lines = child.lines;
            break;
          default:
            if (self.condition) {
              out.push(") {\n" + codeLine.converted); // terminate if, while
              self.condition = false;
            } else {
              out.push(codeLine.converted);
            }
            break;
        } // end switch
      } // end if code
    } // end for
    return out;
  } // end converted
}

new Vue({
  el: "#app",
  data: {
    input: "",
    langIn: "RPG",
    langOut: "Eloquent PHP",
    header: `/*~~~TODO:
  - prepend $ or $row-> to all variable names, e.g. CURDATE to $CURDATE and ID to $row->ID
  - pass row objects to functions if applicable, e.g. getitems() to getitems($row) and function getitems() to function getitems($parentrows)
  - change table aliases to table names, e.g. $addressfields to $customer
~~~*/` + "\n"
  },
  computed: {
    output: function () {
      if (this.input) {
        let code = new CodeBlock(this.input.split("\n"));
        return this.header + code.converted.join("\n");
      }
    }
  },
  methods: {
  }
});
