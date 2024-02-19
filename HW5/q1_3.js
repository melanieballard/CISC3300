//question 1
var x = 8;
var ternaryString = x > 7 ? "true" : "false";
console.log(ternaryString);

//question 2
const grandeLatte = {
    temperature: "iced",
    espressoShots: 2,
    flavorPumps: 4,
    iceScoop: "medium"
}

for(property in grandeLatte){
    console.log(`${property}: ${grandeLatte[property]}`);
}

//question 3
const numArray = [1, 2, 3, 4, 5];
const squaredArray = numArray.map(x => x * x);
console.log(numArray);
console.log(squaredArray);